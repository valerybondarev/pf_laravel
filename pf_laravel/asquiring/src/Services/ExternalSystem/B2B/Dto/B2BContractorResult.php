<?php

namespace App\Services\ExternalSystem\B2B\Dto;

use App\Dto\Api\ApiResult;

class B2BContractorResult extends ApiResult
{
    public ?array $data;

    public function isOk(): bool
    {
        return empty($this->errorCode);
    }
}
