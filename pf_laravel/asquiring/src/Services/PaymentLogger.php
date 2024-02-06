<?php

namespace App\Services;

use App\Intf\PaymentLoggerInterface;
use App\Message\PaymentRequestMessage;
use App\Message\PaymentResponseMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class PaymentLogger implements PaymentLoggerInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected MessageBusInterface $bus
    ) {
    }

    public function logRequest(Uuid $paymentId, string $method, string $url, array $request): Uuid
    {
        $paymentRequestMessage = new PaymentRequestMessage(
            Uuid::v4(),
            new \DateTimeImmutable(),
            $paymentId,
            $method,
            $url,
            $request
        );
        $this->bus->dispatch($paymentRequestMessage);

        return $paymentRequestMessage->id;
    }

    public function logResponse(Uuid $requestId, array $response, bool $success, ?string $errorCode = null, ?string $errorMessage = null): void
    {
        $paymentResponseMessage = new PaymentResponseMessage(
            $requestId,
            new \DateTimeImmutable(),
            $response,
            $success,
            $errorCode,
            $errorMessage
        );
        $this->bus->dispatch($paymentResponseMessage);
    }
}
