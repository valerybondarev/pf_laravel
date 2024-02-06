<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

final class PaymentRequestMessage
{
    public function __construct(
        public Uuid $id,
        public \DateTimeImmutable $createdAt,
        public Uuid $paymentId,
        public string $method,
        public string $url,
        public array $request
    ) {
    }
}
