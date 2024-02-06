<?php

namespace App\Services\PaymentServices\Rshb;

use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class RshbClient implements ApiClientInterface
{
    private ?array $dataArray = [];
    private string $method;
    private \SoapClient $client;

    public function __construct(
        protected ApiConfigInterface $config,
        protected LoggerInterface $logger
    ) {

    }

    public function auth(bool $needAuth): self
    {
        return $this;
    }

    public function withSystem(string $system): self
    {
        return $this;
    }

    public function send(string $method, string $url, array $dataArray = [], array $queryParameters = []): string
    {
        $this->method = $method;
        $this->dataArray = $dataArray;

        return $this->request();
    }

    private function request(): string
    {
        $this->log(Logger::INFO, 'request');

        try {
            $this->client = $this->createClient($this->config);

            $response = $this->client->__soapCall($this->method, [$this->dataArray]);

            if (0 !== $response->errorCode) {
                $this->log(Logger::ERROR, 'error result',
                    [
                        'request method: ' . $this->method,
                        'request headers: ' . $this->client->__getLastRequestHeaders(),
                        'request body: ' . $this->client->__getLastRequest(),
                        'response headers: ' . $this->client->__getLastResponseHeaders(),
                        'response body: ' . $this->client->__getLastResponse(),
                        'result: ' . json_encode($response, JSON_UNESCAPED_UNICODE),
                    ]
                );
            } else {
                $this->log(Logger::INFO, 'response', ['response' => json_decode(json_encode($response, JSON_UNESCAPED_UNICODE), true)]);
            }

            return json_encode($response, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            $this->log(Logger::ERROR, 'error',
                [
                    'request method: ' . $this->method,
                    'request headers: ' . $this->client->__getLastRequestHeaders(),
                    'request body: ' . $this->client->__getLastRequest(),
                    'response headers: ' . $this->client->__getLastResponseHeaders(),
                    'response body: ' . $this->client->__getLastResponse(),
                    'exception code: ' . $e->getCode(),
                    'exception message: ' . $e->getMessage(),
                ]
            );

            return $this->makeError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    private function log(int $level, string $msg, array $context = []): void
    {
        $context['system'] = '[RSHB]';
        $context['method'] = $this->method;
        $context['data'] = $this->getPayload();

        $this->logger->log($level, '[RshbClient]' . ($msg ? ' ' . $msg : ''), $context);
    }

    private function makeError(int $errorCode, ?string $errorMessage): string
    {
        return json_encode([
            $this->config->getErrorCodeName() => $errorCode,
            $this->config->getErrorMessageName() => $errorMessage,
        ]);
    }

    private function getPayload(): array
    {
        $data = $this->dataArray ?? [];

        unset($data['password']);

        return $data;
    }

    private function getWsSecurityTextHeader(string $username, string $password): \SoapHeader
    {
        $auth = <<<XML
                                    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"
                                                   xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-%20wssecurity-utility-1.0.xsd">
                                        <wsse:UsernameToken wsu:Id="UsernameToken-87">
                                            <wsse:Username>$username</wsse:Username>
                                            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">$password</wsse:Password>
                                        </wsse:UsernameToken>
                                    </wsse:Security>
            XML;

        $authValues = new \SoapVar($auth, XSD_ANYXML);

        return new \SoapHeader(
            'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
            'Security',
            $authValues,
            true
        );
    }

    private function createClient(ApiConfigInterface $config): \SoapClient
    {
        $client = new \SoapClient($config->getHost(),
            [
                'trace' => 1,
                'exception' => 0,
                'connection_timeout' => 60,
            ]
        );
        $wsSecurityTextHeader = $this->getWsSecurityTextHeader($config->getLogin(), $config->getPassword());
        $client->__setSoapHeaders([$wsSecurityTextHeader]);

        return $client;
    }
}
