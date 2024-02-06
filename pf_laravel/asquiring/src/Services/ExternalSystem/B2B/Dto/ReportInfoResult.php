<?php

namespace App\Services\ExternalSystem\B2B\Dto;

use App\Dto\Api\ApiResult;

class ReportInfoResult extends ApiResult
{
    public ?string $title;

    public ?array $data;

    public function isOk(): bool
    {
        return empty($this->errorCode);
    }
}
