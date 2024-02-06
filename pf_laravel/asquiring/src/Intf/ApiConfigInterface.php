<?php

namespace App\Intf;

interface ApiConfigInterface
{
    public function getHost(): string;

    public function getLogin(): string;

    public function getPassword(): string;

    public function getReturnUrl(): string;

    public function getErrorCodeName(): string;

    public function getErrorMessageName(): string;

    public function getHeaders(): array;

    public function asJson(): bool;

    public function getAuth(): ?array;

    public function additionalOptions(): array;
}
