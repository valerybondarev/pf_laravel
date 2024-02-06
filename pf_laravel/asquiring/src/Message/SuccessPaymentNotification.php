<?php

namespace App\Message;

final class SuccessPaymentNotification
{
    public function __construct(
        public string $orderId)
    {
    }
}
