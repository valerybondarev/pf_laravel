<?php

namespace App\Services\PaymentServices\Tcs\Dto;

use App\Dto\Api\ApiResult;

final class TCSRegisterResult extends ApiResult
{
    public ?string $orderId;
    public ?string $formUrl;

    public function isOk(): bool
    {
        return !empty($this->orderId);
    }
}
