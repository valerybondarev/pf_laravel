<?php

namespace App\Services\PaymentServices\Alfa\Dto;

use App\Dto\Api\ApiResult;

class AlfaQrGetResult extends ApiResult
{
    public ?string $payload = null;
    public ?string $qrId = null;
    public ?string $qrStatus = null;

    public function isOk(): bool
    {
        return !empty($this->qrId);
    }
}
