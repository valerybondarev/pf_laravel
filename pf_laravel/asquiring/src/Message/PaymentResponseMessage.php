<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

final class PaymentResponseMessage
{
    public function __construct(
        public ?Uuid $requestId,
        public \DateTimeImmutable $createdAt,
        public array $response,
        public bool $success,
        public ?string $errorCode,
        public ?string $errorMessage
    ) {
    }
}
