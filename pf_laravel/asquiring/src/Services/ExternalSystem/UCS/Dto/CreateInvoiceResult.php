<?php

namespace App\Services\ExternalSystem\UCS\Dto;

use App\Dto\Api\ApiResult;
use Symfony\Component\HttpFoundation\Response;

class CreateInvoiceResult extends ApiResult
{
    public ?string $id;

    public ?string $number;

    public ?float $amount;

    public bool $canceled;

    public bool $processed;

    public bool $payConfirmed;

    public function isOk(): bool
    {
        return is_null($this->errorCode);
    }

    public function collectErrors(array $response): bool
    {
        if (array_key_exists('Errors', $response)) {
            $messages = array_map(function ($e) {
                return $e['Title'];
            }, $response['Errors']);
            $this->errorCode = Response::HTTP_BAD_REQUEST;
            $this->errorMessage = implode(PHP_EOL, $messages);

            return true;
        }

        return false;
    }
}
