<?php

namespace App\Services;

use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseApiClient implements ApiClientInterface
{
    protected ?array $dataArray = [];
    protected ?array $queryParameters = [];
    protected string $method;
    protected string $url;
    protected array $options = [];
    private ?string $system = null;
    private bool $needAuth = true;

    public function __construct(
        protected ApiConfigInterface $config,
        protected HttpClientInterface $httpClient,
        protected LoggerInterface $logger
    ) {
    }

    public function auth(bool $needAuth): self
    {
        $this->needAuth = $needAuth;

        return $this;
    }

    public function send(string $method, string $url, array $dataArray = [], array $queryParameters = []): string
    {
        $this->method = $method;
        $this->url = $this->config->getHost() . $url;
        $this->dataArray = $dataArray;
        $this->queryParameters = $queryParameters;
        $this->options = $this->prepareOptions();

        $result = $this->request();

        $this->system = null;

        return $result['response'];
    }

    protected function request(): array
    {
        $this->log(Logger::INFO, 'request');

        try {
            $response = $this->httpClient->request($this->method, $this->url, $this->options);

            $result = $this->makeResponse($response->getStatusCode(), $response->getContent());
            $this->log(Logger::INFO, 'response', ['response' => $response]);

            return $result;
        } catch (ClientExceptionInterface|ServerExceptionInterface $e) {
            $msg = $e->getResponse()->getContent(false);
            $responseArray = json_decode($msg, true);
            if (is_null($responseArray)) {
                $msg = $e->getMessage();
            }
            $this->log(Logger::ERROR, 'error', ['status' => $e->getCode(), 'error' => $msg]);

            return $this->makeResponse($e->getResponse()->getStatusCode(), $msg);
        } catch (TransportExceptionInterface $e) {
            $this->log(Logger::ERROR, 'error', ['status' => Response::HTTP_BAD_REQUEST, 'error' => $e->getMessage()]);

            return $this->makeResponse(Response::HTTP_BAD_REQUEST,
                $this->makeError(Response::HTTP_BAD_REQUEST, 'Ошибка вызова ' . $this->system ?? 'внешнего сервиса'));
        } catch (ExceptionInterface $e) {
            $this->log(Logger::ERROR, 'error', ['status' => Response::HTTP_BAD_REQUEST, 'error' => $e->getMessage()]);

            return $this->makeResponse(Response::HTTP_BAD_REQUEST,
                $this->makeError(Response::HTTP_BAD_REQUEST, $e->getMessage()));
        }
    }

    private function log(int $level, string $msg, array $context = []): void
    {
        if ($this->system) {
            $context['system'] = '[' . $this->system . ']';
        }
        $context['method'] = $this->method;
        $context['url'] = $this->url;
        $context['data'] = $this->getPayload();

        $this->logger->log($level, '[BaseApiClient]' . ($msg ? ' ' . $msg : ''), $context);
    }

    private function makeResponse(int $statusCode, ?string $response): array
    {
        return [
            'statusCode' => $statusCode,
            'response' => $response,
        ];
    }

    private function makeError(int $errorCode, ?string $errorMessage): string
    {
        return json_encode([
            $this->config->getErrorCodeName() => $errorCode,
            $this->config->getErrorMessageName() => $errorMessage,
        ]);
    }

    public function withSystem(string $system): ApiClientInterface
    {
        $this->system = $system;

        return $this;
    }

    protected function prepareOptions(): array
    {
        $options = [
            'headers' => $this->config->getHeaders(),
            'query' => $this->queryParameters,
        ];

        $auth = $this->needAuth ? $this->config->getAuth() : [];
        if (!empty($auth)) {
            $options = array_merge($options, $auth);
        }

        if ($this->config->asJson()) {
            $options['json'] = $this->dataArray;
        } else {
            $options['body'] = $this->dataArray;
        }

        return array_merge($options, $this->config->additionalOptions());
    }

    private function getPayload(): array
    {
        $data = $this->dataArray ?? $this->queryParameters ?? [];

        unset($data['password']);

        return $data;
    }
}
