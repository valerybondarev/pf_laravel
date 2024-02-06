<?php

namespace App\Services\ExternalSystem\B2B;

use App\Dto\Api\ApiResult;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Services\ExternalSystem\B2B\Dto\B2BContractorResult;
use App\Services\ExternalSystem\B2B\Dto\InfoParam;
use App\Services\ExternalSystem\B2B\Dto\InfoResult;
use App\Services\ExternalSystem\B2B\Dto\PaymentNotifyParam;
use App\Services\ExternalSystem\B2B\Dto\PaymentNotifyResult;
use App\Services\ExternalSystem\B2B\Dto\ReportInfoResult;

class B2BApi
{
    private const METHOD_DETAIL = '/api/acquiring-info';
    private const METHOD_REPORT_DETAIL = '/api/acquiring-info-report';
    private const METHOD_GET_CONTRACTOR = '/api/acquiring-contractor';
    private const METHOD_PAYMENT_NOTIFY = '/api/acquiring-payment-notify';
    private const SYSTEM = 'B2B';

    public function __construct(
        private ApiClientInterface $client,
        private ApiConfigInterface $config
    ) {
    }

    /**
     * @return InfoResult
     */
    public function info(InfoParam $param): ApiResult
    {
        $data = (array) $param;
        $responseJson = $this->doCall('POST', self::METHOD_DETAIL, $data, false);
        $result = new InfoResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->title = array_key_exists('title', $responseArray) ? $responseArray['title'] : '';
            $result->data = array_key_exists('data', $responseArray) ? $responseArray['data'] : [];
        }

        return $result;
    }

    public function paymentNotify(PaymentNotifyParam $param): ApiResult
    {
        $data = (array) $param;
        $responseJson = $this->doCall('POST', self::METHOD_PAYMENT_NOTIFY, $data, false);
        $result = new PaymentNotifyResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->data = array_key_exists('data', $responseArray) ? $responseArray['data'] : [];
        }

        return $result;
    }

    public function infoReport(string $reportGuid): ApiResult
    {
        $responseJson = $this->doCall('POST', self::METHOD_REPORT_DETAIL, ['guid' => $reportGuid]);
        $result = new ReportInfoResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->title = array_key_exists('title', $responseArray) ? $responseArray['title'] : '';
            $result->data = array_key_exists('data', $responseArray) ? $responseArray['data'] : [];
        }

        return $result;
    }

    /**
     * @return B2BContractorResult
     */
    public function getContractor(InfoParam $param): ApiResult
    {
        $data = (array) $param;
        $responseJson = $this->doCall('POST', self::METHOD_GET_CONTRACTOR, $data, false);
        $result = new B2BContractorResult($responseJson, $this->config->getErrorCodeName(), $this->config->getErrorMessageName());
        if ($result->isOk()) {
            $responseArray = json_decode($responseJson, true);
            $result->data = array_key_exists('data', $responseArray) ? $responseArray['data'] : [];
        }

        return $result;
    }

    private function doCall(string $method, string $url, array $data, bool $auth = true): string
    {
        return $this->client
            ->auth($auth)
            ->withSystem(self::SYSTEM)
            ->send($method, $url, $data);
    }
}
