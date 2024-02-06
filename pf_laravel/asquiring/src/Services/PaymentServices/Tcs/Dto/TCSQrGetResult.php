<?php

namespace App\Services\PaymentServices\Tcs\Dto;

use App\Dto\Api\ApiResult;

final class TCSQrGetResult extends ApiResult
{
    public ?string $payload = null;

    public function isOk(): bool
    {
        return !empty($this->payload);
    }
}
