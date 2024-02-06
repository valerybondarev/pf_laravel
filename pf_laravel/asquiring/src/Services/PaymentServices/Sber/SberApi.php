<?php

namespace App\Services\PaymentServices\Sber;

use App\Dto\Api\ApiResult;
use App\Enum\PaymentType;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Intf\PaymentLoggerInterface;
use App\Services\PaymentServices\Sber\Dto\SberQrGetParam;
use App\Services\PaymentServices\Sber\Dto\SberQrGetResult;
use App\Services\PaymentServices\Sber\Dto\SberRegisterParam;
use App\Services\PaymentServices\Sber\Dto\SberRegisterResult;
use App\Services\PaymentServices\Sber\Dto\SberStatusParam;
use App\Services\PaymentServices\Sber\Dto\SberStatusResult;
use Symfony\Component\Uid\Uuid;

class SberApi
{
    private const METHOD_REGISTER = 'register.do';
    private const METHOD_STATUS = 'getOrderStatusExtended.do';
    private const METHOD_QR_GET = 'sbp/c2b/qr/dynamic/get.do';

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config,
        private PaymentLoggerInterface $paymentLogger
    ) {
    }

    private function doCall(Uuid $paymentId, string $method, string $url, array $data, $responseHandler): ApiResult
    {
        $paymentRequestId = $this->paymentLogger->logRequest($paymentId, $method, $url, $data);
        $data['userName'] = $this->config->getLogin();
        $data['password'] = $this->config->getPassword();
        $responseJson = $this->client->send($method, $url, $data);

        return $responseHandler($paymentRequestId, $responseJson);
    }

    /**
     * @return SberRegisterResult
     */
    public function register(Uuid $orderId, Uuid $paymentId, SberRegisterParam $param): ApiResult
    {
        $data = [
            'orderNumber' => $param->orderNumber,
            'amount' => $param->amount,
            'returnUrl' => str_replace('{orderId}', $orderId, $this->config->getReturnUrl()),
            'jsonParams' => [
                'paymentDescription' => $param->paymentDescription,
            ],
        ];
        if (!is_null($param->expirationDate)) {
            $data['expirationDate'] = $param->expirationDate->format('Y-m-d\TH:i:s');
        }
        if (!empty($param->ucsBill)) {
            $data['jsonParams']['ucsBill'] = $param->ucsBill;
        }
        if (!empty($param->ucsInvoiceId)) {
            $data['jsonParams']['ucsInvoiceId'] = $param->ucsInvoiceId;
        }
        $data['jsonParams'] = json_encode($data['jsonParams']);

        return $this->doCall($paymentId, 'POST', self::METHOD_REGISTER, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new SberRegisterResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderId = array_key_exists('orderId', $responseArray) ? $responseArray['orderId'] : '';
                $result->formUrl = array_key_exists('formUrl', $responseArray) ? $responseArray['formUrl'] : '';
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    /**
     * Определение типа оплаты: карта или qr.
     * Для qr в ответе будет блок merchantOrderParams и внутри QR_ID.
     */
    private function detectPayType(array $data): string
    {
        if (array_key_exists('merchantOrderParams', $data)) {
            foreach ($data['merchantOrderParams'] as $item) {
                if ('QR_ID' === $item['name']) {
                    return PaymentType::QR;
                }
            }
        }

        return PaymentType::CARD;
    }

    private function getPayedAt(array $data): \DateTime
    {
        $timestamp = $data['authDateTime'];

        return \DateTime::createFromFormat('U', intval($timestamp / 1000));
    }

    /**
     * @return SberStatusResult
     */
    public function status(Uuid $paymentId, SberStatusParam $param): ApiResult
    {
        $data = (array) $param;

        return $this->doCall($paymentId, 'POST', self::METHOD_STATUS, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new SberStatusResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderStatus = array_key_exists('orderStatus', $responseArray) ? $responseArray['orderStatus'] : null;
                $result->actionCodeDescription = array_key_exists('actionCodeDescription', $responseArray) ? $responseArray['actionCodeDescription'] : null;
                $result->payedType = $this->detectPayType($responseArray);
                if ($result->isOk()) {
                    $result->payedAt = $this->getPayedAt($responseArray);
                }
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    /**
     * @return SberQrGetResult
     */
    public function qrGet(Uuid $paymentId, SberQrGetParam $param): ApiResult
    {
        $data = (array) $param;

        return $this->doCall($paymentId, 'POST', self::METHOD_QR_GET, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new SberQrGetResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->payload = array_key_exists('payload', $responseArray) ? $responseArray['payload'] : null;
                $result->qrId = array_key_exists('qrId', $responseArray) ? $responseArray['qrId'] : null;
                $result->qrStatus = array_key_exists('qrStatus', $responseArray) ? $responseArray['qrStatus'] : null;
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }
}
