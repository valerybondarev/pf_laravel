<?php

namespace App\Services\ExternalSystem\UCS;

use App\Dto\Api\ApiResult;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Services\ExternalSystem\UCS\Dto\CreateInvoiceResult;
use App\Services\ExternalSystem\UCS\Dto\InfoParam;
use App\Services\ExternalSystem\UCS\Dto\InfoResult;
use App\Services\ExternalSystem\UCS\Dto\SuccessPaymentNotificationResult;

class UCSApi
{
    private const METHOD_CREATE_INVOICE = '/hs/PaymentService/CreateInvoice';
    private const METHOD_SUCCESS_PAYMENT_NOTIFICATION = '/hs/PaymentService/SuccessPaymentNotification';
    private const METHOD_DETAIL = '/hs/PaymentService/DetailInfo';

    private const SYSTEM = 'UCS';

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config
    ) {
    }

    /**
     * @return CreateInvoiceResult
     */
    public function createInvoice(array $param): ApiResult
    {
        $data = ['data' => $param];
        $responseJson = $this->doCall('POST', self::METHOD_CREATE_INVOICE, $data);
        $result = new CreateInvoiceResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->id = $responseArray['ID'];
            $result->number = $responseArray['NumberDoc'];
            $result->amount = $responseArray['Summ'];
            $result->canceled = $responseArray['Canceled'];
            $result->processed = $responseArray['Processed'];
            $result->payConfirmed = $responseArray['PayConfirmed'];
        }

        return $result;
    }

    public function successPaymentNotification(array $param): ApiResult
    {
        $data = ['data' => $param];
        $responseJson = $this->doCall('POST', self::METHOD_SUCCESS_PAYMENT_NOTIFICATION, $data);
        $result = new SuccessPaymentNotificationResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->id = $responseArray['ID'];
            $result->number = $responseArray['NumberDoc'];
            $result->amount = $responseArray['Summ'];
            $result->canceled = $responseArray['Canceled'];
            $result->processed = $responseArray['Processed'];
            $result->payConfirmed = $responseArray['PayConfirmed'];
            $result->status = $responseArray['Status'];
        }

        return $result;
    }

    /**
     * @return InfoResult
     */
    public function info(InfoParam $param): ApiResult
    {
        $data = ['data' => (array) $param];
        $responseJson = $this->doCall('POST', self::METHOD_DETAIL, $data);
        $result = new InfoResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->title = array_key_exists('title', $responseArray) ? $responseArray['title'] : '';
            $result->data = array_key_exists('data', $responseArray) ? $responseArray['data'] : [];
        }

        return $result;
    }

    private function doCall(string $method, string $url, array $data): string
    {
        return $this->client
            ->withSystem(self::SYSTEM)
            ->send($method, $url, $data);
    }
}
