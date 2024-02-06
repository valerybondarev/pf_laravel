<?php

namespace App\Services\ExternalSystem\B2B;

use App\Intf\ApiConfigInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class B2BClientConfig implements ApiConfigInterface
{
    public function __construct(
        private string $host,
        private RequestStack $requestStack
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getLogin(): string
    {
        return '';
    }

    public function getPassword(): string
    {
        return '';
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
            'content-type' => 'application/json',
        ];
    }

    public function asJson(): bool
    {
        return true;
    }

    public function getAuth(): ?array
    {
        $request = $this->requestStack->getCurrentRequest();
        $xAuthToken = $request->headers->get('X-AUTH-TOKEN');

        if (preg_match("/Bearer\s(\S+)/", $xAuthToken, $m) && 'null' != $m[1]) {
            return ['auth_bearer' => $m[1]];
        }

        return [];
    }

    public function additionalOptions(): array
    {
        return [];
    }
}
