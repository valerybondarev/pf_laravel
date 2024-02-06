<?php

namespace App\Dto\Api;

use App\Enum\ErrorCode;

abstract class ApiResult
{
    public array $response;
    public ?string $errorCode;
    public ?string $errorMessage;

    public function __construct(string $response, string $errorCodeName, string $errorMessageName)
    {
        $responseArray = json_decode($response, true) ?? [
            $errorCodeName => ErrorCode::BAD_RESPONSE,
            $errorMessageName => $response,
        ];

        $this->response = $responseArray;
        if (!$this->collectErrors($responseArray)) {
            $this->errorCode = array_key_exists($errorCodeName, $responseArray) ? $responseArray[$errorCodeName] : null;
            $this->errorMessage = array_key_exists($errorMessageName, $responseArray) ? $responseArray[$errorMessageName] : null;
        }
    }

    abstract public function isOk(): bool;

    public function collectErrors(array $response): bool
    {
        return false;
    }
}
