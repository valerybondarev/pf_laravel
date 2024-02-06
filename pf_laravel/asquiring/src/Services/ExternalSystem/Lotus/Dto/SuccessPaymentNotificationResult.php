<?php

namespace App\Services\ExternalSystem\Lotus\Dto;

use App\Dto\Api\ApiResult;

class SuccessPaymentNotificationResult extends ApiResult
{
    public function isOk(): bool
    {
        return is_null($this->errorCode);
    }
}
