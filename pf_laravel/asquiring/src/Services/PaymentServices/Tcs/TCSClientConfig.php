<?php

namespace App\Services\PaymentServices\Tcs;

use App\Intf\ApiConfigInterface;

class TCSClientConfig implements ApiConfigInterface
{
    public function __construct(
        private string $host,
        private string $login,
        private string $password,
        private string $returnUrl
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function getErrorCodeName(): string
    {
        return 'ErrorCode';
    }

    public function getErrorMessageName(): string
    {
        return 'Message';
    }

    public function getHeaders(): array
    {
        return [
            'content-type' => 'application/json',
        ];
    }

    public function asJson(): bool
    {
        return false;
    }

    public function getAuth(): ?array
    {
        return [];
    }

    public function additionalOptions(): array
    {
        return [];
    }
}
