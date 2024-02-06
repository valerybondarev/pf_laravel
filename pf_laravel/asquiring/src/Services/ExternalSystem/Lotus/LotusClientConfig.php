<?php

namespace App\Services\ExternalSystem\Lotus;

use App\Intf\ApiConfigInterface;

class LotusClientConfig implements ApiConfigInterface
{
    public function __construct(
        private string $host,
        private string $login,
        private string $password
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
        return '';
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
        return ['content-type' => 'application/json'];
    }

    public function asJson(): bool
    {
        return true;
    }

    public function getAuth(): ?array
    {
        return ['auth_basic' => [$this->getLogin(), $this->getPassword()]];
    }

    public function additionalOptions(): array
    {
        return [
            'verify_peer' => false,
            'verify_host' => false,
        ];
    }
}
