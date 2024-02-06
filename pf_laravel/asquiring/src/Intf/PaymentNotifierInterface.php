<?php

namespace App\Intf;

use App\Entity\Order;
use App\Entity\Payment;

interface PaymentNotifierInterface
{
    public function support(Order $order, Payment $payment): bool;

    public function notify(Order $order, Payment $payment): void;
}
