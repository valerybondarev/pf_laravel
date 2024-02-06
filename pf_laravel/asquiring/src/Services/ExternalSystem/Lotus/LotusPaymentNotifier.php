<?php

namespace App\Services\ExternalSystem\Lotus;

use App\Entity\Order;
use App\Entity\Payment;
use App\Intf\PaymentNotifierInterface;
use App\Message\LotusNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class LotusPaymentNotifier implements PaymentNotifierInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function support(Order $order, Payment $payment): bool
    {
        return $order->isLotus();
    }

    public function notify(Order $order, Payment $payment): void
    {
        $notification = new LotusNotification($order->getId()->toRfc4122(), $order->isSuccess());

        $this->bus->dispatch($notification);
    }
}
