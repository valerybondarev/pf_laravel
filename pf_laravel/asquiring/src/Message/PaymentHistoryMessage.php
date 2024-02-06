<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

final class PaymentHistoryMessage
{
    public function __construct(
        public \DateTimeImmutable $createdAt,
        public Uuid $paymentId,
        public array $payload
    ) {
    }
}
