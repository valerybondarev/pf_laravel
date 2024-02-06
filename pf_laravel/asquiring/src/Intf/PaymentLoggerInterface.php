<?php

namespace App\Intf;

use Symfony\Component\Uid\Uuid;

interface PaymentLoggerInterface
{
    public function logRequest(Uuid $paymentId, string $method, string $url, array $request): Uuid;

    public function logResponse(Uuid $requestId, array $response, bool $success, ?string $errorCode = null, ?string $errorMessage = null): void;
}
