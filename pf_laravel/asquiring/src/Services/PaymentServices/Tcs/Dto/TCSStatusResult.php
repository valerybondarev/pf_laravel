<?php

namespace App\Services\PaymentServices\Tcs\Dto;

use App\Dto\Api\ApiResult;

final class TCSStatusResult extends ApiResult
{
    public ?string $orderStatus = null;
    public ?\DateTime $payedAt = null;

    public function isOk(): bool
    {
        return 'CONFIRMED' === $this->orderStatus;
    }
}
