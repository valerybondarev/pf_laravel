<?php

namespace App\Services\PaymentServices\Alfa\Dto;

use App\Dto\Api\ApiResult;

class AlfaRegisterResult extends ApiResult
{
    public ?string $orderId;
    public ?string $formUrl;

    public function isOk(): bool
    {
        return !empty($this->orderId);
    }
}
