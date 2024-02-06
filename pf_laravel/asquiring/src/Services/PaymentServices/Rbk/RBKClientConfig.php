<?php

namespace App\Services\PaymentServices\Rbk;

use App\Intf\ApiConfigInterface;
use Symfony\Component\Uid\Uuid;

class RBKClientConfig implements ApiConfigInterface
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
        return [
            'content-type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $this->getPassword(),
            'X-Request-ID' => str_replace('-', '', Uuid::v4()->toRfc4122()),
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
