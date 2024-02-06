<?php

namespace App\Services\PaymentServices\Tcs;

use App\Dto\Api\ApiResult;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Intf\PaymentLoggerInterface;
use App\Services\PaymentServices\Tcs\Dto\TCSQrGetParam;
use App\Services\PaymentServices\Tcs\Dto\TCSQrGetResult;
use App\Services\PaymentServices\Tcs\Dto\TCSRegisterParam;
use App\Services\PaymentServices\Tcs\Dto\TCSRegisterResult;
use App\Services\PaymentServices\Tcs\Dto\TCSStatusParam;
use App\Services\PaymentServices\Tcs\Dto\TCSStatusResult;
use Symfony\Component\Uid\Uuid;

class TCSApi
{
    private const METHOD_REGISTER = 'Init';
    private const METHOD_STATUS = 'GetState';
    private const METHOD_QR_GET = 'GetQr';

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config,
        private PaymentLoggerInterface $paymentLogger
    ) {
    }

    private function doCall(Uuid $paymentId, string $method, string $url, array $data, $responseHandler): ApiResult
    {
        $paymentRequestId = $this->paymentLogger->logRequest($paymentId, $method, $url, $data);
        $data['TerminalKey'] = $this->config->getLogin();
        $data['token'] = $this->getRequestSign($data);
        $responseJson = $this->client->send($method, $url, $data);

        return $responseHandler($paymentRequestId, $responseJson);
    }

    /**
     * Подпись запроса.
     * https://www.tinkoff.ru/kassa/develop/api/request-sign/.
     */
    private function getRequestSign(array $data): string
    {
        $data['Password'] = $this->config->getPassword();
        ksort($data);
        $value = array_reduce($data,
            function (string $result, $item) {
                return $result . $item;
            }, '');

        return hash('sha256', $value);
    }

    /**
     * @return TCSRegisterResult
     */
    public function register(Uuid $orderId, Uuid $paymentId, TCSRegisterParam $param): ApiResult
    {
        $data = [
            'OrderId' => $param->OrderId,
            'Amount' => $param->Amount,
            'returnUrl' => str_replace('{orderId}', $orderId, $this->config->getReturnUrl()),
        ];
        if (!is_null($param->RedirectDueDate)) {
            $data['RedirectDueDate'] = $param->RedirectDueDate->format('Y-m-d\TH:i:s');
        }

        return $this->doCall($paymentId, 'POST', self::METHOD_REGISTER, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new TCSRegisterResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderId = array_key_exists('PaymentId', $responseArray) ? (string) $responseArray['PaymentId'] : '';
                $result->formUrl = array_key_exists('PaymentURL', $responseArray) ? $responseArray['PaymentURL'] : '';
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    /**
     * @return TCSStatusResult
     */
    public function status(Uuid $paymentId, TCSStatusParam $param): ApiResult
    {
        $data = (array) $param;

        return $this->doCall($paymentId, 'POST', self::METHOD_STATUS, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new TCSStatusResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->orderStatus = array_key_exists('Status', $responseArray) ? $responseArray['Status'] : null;
                if ($result->isOk()) {
                    $result->payedAt = new \DateTime();
                }
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }

    /**
     * @return TCSQrGetResult
     */
    public function qrGet(Uuid $paymentId, TCSQrGetParam $param): ApiResult
    {
        $data = (array) $param;

        return $this->doCall($paymentId, 'POST', self::METHOD_QR_GET, $data,
            function (Uuid $requestId, string $responseJson) {
                $result = new TCSQrGetResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
                $responseArray = json_decode($responseJson, true) ?? [];
                $result->payload = array_key_exists('Data', $responseArray) ? $responseArray['Data'] : null;
                $this->paymentLogger->logResponse($requestId, $responseArray, $result->isOk(), $result->errorCode, $result->errorMessage);

                return $result;
            }
        );
    }
}
