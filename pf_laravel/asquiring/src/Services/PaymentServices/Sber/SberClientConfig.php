<?php

namespace App\Services\PaymentServices\Sber;

use App\Intf\ApiConfigInterface;

class SberClientConfig implements ApiConfigInterface
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
        return 'errorCode';
    }

    public function getErrorMessageName(): string
    {
        return 'errorMessage';
    }

    public function getHeaders(): array
    {
        return [
            'content-type' => 'application/x-www-form-urlencoded',
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