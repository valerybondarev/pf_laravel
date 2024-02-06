<?php

namespace App\Services\PaymentServices\Rbk\Dto;

use App\Dto\Api\ApiResult;

class RBKStatusResult extends ApiResult
{
    public ?string $status;
    public ?string $reason;

    public function isOk(): bool
    {
        return !empty('paid' === $this->status);
    }
}
