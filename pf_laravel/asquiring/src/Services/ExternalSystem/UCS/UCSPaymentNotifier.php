<?php

namespace App\Services\ExternalSystem\UCS;

use App\Entity\Order;
use App\Entity\Payment;
use App\Exception\UCSException;
use App\Intf\PaymentNotifierInterface;
use App\Message\UCSPaymentNotification;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;

class UCSPaymentNotifier implements PaymentNotifierInterface
{
    public function __construct(private UCSService $ucsService,
                                private LoggerInterface $logger,
                                private MessageBusInterface $bus)
    {
    }

    public function support(Order $order, Payment $payment): bool
    {
        return true === $order->getUcsBillNeed();
    }

    public function notify(Order $order, Payment $payment): void
    {
        try {
            $this->ucsService->successPaymentNotification($order, $payment);
        } catch (UCSException $e) {
            $this->logger->error($e->getMessage(), ['orderId' => $order->getId()->toRfc4122(), 'exception' => $e]);
            $this->logger->info('[UCS] Sending payment notification with rabbitmq.', ['orderId' => $order->getId()->toRfc4122()]);

            $this->bus->dispatch($this->createMessage($order, $payment))->withoutStampsOfType(BusNameStamp::class);
        }
    }

    #[Pure]
    private function createMessage(Order $order, Payment $payment): UCSPaymentNotification
    {
        return new UCSPaymentNotification($order->getUcsInvoiceId(), $order->isSuccess(), $payment->getProviderType(),
            $payment->getTransactionId());
    }
}
