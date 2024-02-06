<?php

namespace App\Services\PaymentServices\Sber\Dto;

use App\Dto\Api\ApiResult;

class SberQrGetResult extends ApiResult
{
    public ?string $payload = null;
    public ?string $qrId = null;
    public ?string $qrStatus = null;

    public function isOk(): bool
    {
        return !empty($this->qrId);
    }
}
