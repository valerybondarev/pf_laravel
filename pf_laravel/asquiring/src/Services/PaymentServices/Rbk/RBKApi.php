<?php

namespace App\Services\PaymentServices\Rbk;

use App\Dto\Api\ApiResult;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Intf\PaymentLoggerInterface;
use App\Services\PaymentServices\Rbk\Dto\RBKRegisterParam;
use App\Services\PaymentServices\Rbk\Dto\RBKRegisterResult;
use App\Services\PaymentServices\Rbk\Dto\RBKStatusParam;
use App\Services\PaymentServices\Rbk\Dto\RBKStatusResult;
use Symfony\Component\Uid\Uuid;

class RBKApi
{
    private const METHOD_CREATE_INVOICE = 'processing/invoices';
    private const METHOD_GET_INVOICE = 'processing/invoices/';

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config,
        private PaymentLoggerInterface $paymentLogger
    ) {
    }

    private function doCall(Uuid $paymentId, string $method, string $url, array $data, $responseHandler): ApiResult
    {
        $paymentRequestId = $this->paymentLogger->logRequest($paymentId, $method, $url, $data);
        $responseJson = $this->client->send($method, $url, $data);

        return $responseHandler($paymentRequestId, $responseJson);
    }

    /**
     * @return RBKRegisterResult
     */
    public function register(Uuid $paymentId, RBKRegisterParam $param): ApiResult
    {
        $data = (array) $param;
        $data['shopID'] = $this->config->getLogin();

        return $this->doCall($paymentId, 'POST', self::METHOD_CREATE_INVOICE, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new RBKRegisterResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                if (array_key_exists('invoice', $responseArray)) {
                    $result->invoiceId = $responseArray['invoice']['id'];
                }
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    /**
     * @return RBKStatusResult
     */
    public function status(Uuid $paymentId, RBKStatusParam $param): ApiResult
    {
        return $this->doCall($paymentId, 'GET', self::METHOD_GET_INVOICE . $param->invoiceId, [],
            function (Uuid $requestId, string $responseJson) {
                $result = new RBKStatusResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->status = array_key_exists('status', $responseArray) ? $responseArray['status'] : null;
                $result->reason = array_key_exists('reason', $responseArray) ? $responseArray['reason'] : null;
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }
}
