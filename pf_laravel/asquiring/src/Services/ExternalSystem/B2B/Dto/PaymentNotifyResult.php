<?php

namespace App\Services\ExternalSystem\B2B\Dto;

use App\Dto\Api\ApiResult;

class PaymentNotifyResult extends ApiResult
{
    public ?array $data;

    public function isOk(): bool
    {
            return empty($this->errorCode);
    }
}