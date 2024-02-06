<?php

namespace App\Services\ExternalSystem\B2B\Dto;

use App\Dto\Api\ApiResult;

class InfoResult extends ApiResult
{
    public ?string $title;

    public ?array $data;

    public function isOk(): bool
    {
        return empty($this->errorCode);
    }
}
