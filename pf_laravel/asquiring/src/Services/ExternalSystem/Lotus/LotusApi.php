<?php

namespace App\Services\ExternalSystem\Lotus;

use App\Dto\Api\ApiResult;
use App\Intf\ApiClientInterface;
use App\Intf\ApiConfigInterface;
use App\Services\ExternalSystem\Lotus\Dto\InfoParam;
use App\Services\ExternalSystem\Lotus\Dto\InfoResult;

class LotusApi
{
    private const METHOD_DETAIL = '/PayInfo';
    private const SYSTEM = 'LOTUS';

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
