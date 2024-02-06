<?php

namespace App\Services\PaymentServices\Rbk\Dto;

use App\Dto\Api\ApiResult;

class RBKRegisterResult extends ApiResult
{
    public ?string $invoiceId;

    public function isOk(): bool
    {
        return !empty($this->invoiceId);
    }
}
