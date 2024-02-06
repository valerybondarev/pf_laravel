<?php

namespace App\Services\PaymentServices\Sber\Dto;

use App\Dto\Api\ApiResult;

class SberRegisterResult extends ApiResult
{
    public ?string $orderId;
    public ?string $formUrl;

    public function isOk(): bool
    {
        return !empty($this->orderId);
    }
}
