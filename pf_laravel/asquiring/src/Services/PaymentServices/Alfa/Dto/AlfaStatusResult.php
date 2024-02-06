<?php

namespace App\Services\PaymentServices\Alfa\Dto;

use App\Dto\Api\ApiResult;

class AlfaStatusResult extends ApiResult
{
    private const STATUS_SUCCESS = [2];
    private const STATUS_FAIL = [3, 6];

    public ?int $orderStatus;
    public ?string $actionCodeDescription;
    public ?\DateTime $payedAt = null;
    public ?string $payedType = null;

    public function isOk(): bool
    {
        return 2 === $this->orderStatus;
    }

    public function isSuccess(): bool
    {
        return in_array($this->orderStatus, self::STATUS_SUCCESS);
    }

    public function isFail(): bool
    {
        return in_array($this->orderStatus, self::STATUS_FAIL);
    }
}
