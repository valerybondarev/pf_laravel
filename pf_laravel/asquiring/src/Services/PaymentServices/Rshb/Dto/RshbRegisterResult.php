<?php

namespace App\Services\PaymentServices\Rshb\Dto;

use App\Dto\Api\ApiResult;

class RshbRegisterResult extends ApiResult
{
    public ?string $orderId;
    public ?string $formUrl;

    public function isOk(): bool
    {
        return !empty($this->orderId);
    }
}
