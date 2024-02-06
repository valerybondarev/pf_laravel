<?php

namespace App\Services\PaymentServices\Rshb;

use App\Dto\Api\ApiResult;
use App\Enum\PaymentType;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Intf\PaymentLoggerInterface;
use App\Services\PaymentServices\Rshb\Dto\RshbRegisterParam;
use App\Services\PaymentServices\Rshb\Dto\RshbRegisterResult;
use App\Services\PaymentServices\Rshb\Dto\RshbStatusParam;
use App\Services\PaymentServices\Rshb\Dto\RshbStatusResult;
use Symfony\Component\Uid\Uuid;

class RshbApi
{
    private const METHOD_REGISTER = 'registerP2P';
    private const METHOD_STATUS = 'getP2PStatus';
    private const CURRENCY_RUB = 643;
    private const REGISTER_FEATURES = ['WITHOUT_TO_CARD'];

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config,
        private PaymentLoggerInterface $paymentLogger
    ) {
    }

    private function doCall(Uuid $paymentId, string $method, array $data, $responseHandler): ApiResult
    {
        $paymentRequestId = $this->paymentLogger->logRequest($paymentId, $method, '', $data);
        $responseJson = $this->client->send($method, '', $data);

        return $responseHandler($paymentRequestId, $responseJson);
    }

    /**
     * @return RshbRegisterResult
     */
    public function register(Uuid $orderId, Uuid $paymentId, RshbRegisterParam $param): ApiResult
    {
        $data = [
            'amount' => $param->amount,
            'currency' => self::CURRENCY_RUB,
            'orderNumber' => $param->orderNumber,
            'orderDescription' => $param->paymentDescription,
            'returnUrl' => str_replace('{orderId}', $orderId, $this->config->getReturnUrl()),
            'features' => self::REGISTER_FEATURES,
        ];

        return $this->doCall($paymentId, self::METHOD_REGISTER, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new RshbRegisterResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderId = array_key_exists('orderId', $responseArray) ? $responseArray['orderId'] : '';
                $result->formUrl = array_key_exists('formUrl', $responseArray) ? $responseArray['formUrl'] : '';
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    private function getPayedAt(array $data): ?\DateTime
    {
        $dt = $data['operationList']['datetime'] ?? null;

        return !is_null($dt) ? new \DateTime($dt) : null;
    }

    /**
     * @return RshbStatusResult
     */
    public function status(Uuid $paymentId, RshbStatusParam $param): ApiResult
    {
        $data = (array) $param;

        return $this->doCall($paymentId, self::METHOD_STATUS, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new RshbStatusResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderStatus = array_key_exists('orderStatus', $responseArray) ? $responseArray['orderStatus'] : null;
                $result->actionCodeDescription = array_key_exists('actionCodeDescription', $responseArray) ? $responseArray['actionCodeDescription'] : null;
                $result->payedType = PaymentType::CARD;
                if ($result->isOk()) {
                    $result->payedAt = $this->getPayedAt($responseArray);
                }
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }
}
