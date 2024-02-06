<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

final class VisitedMessage
{
    public function __construct(
        public \DateTimeImmutable $createdAt,
        public Uuid $orderId,
        public \DateTimeImmutable $visitedAt
    ) {
    }
}
