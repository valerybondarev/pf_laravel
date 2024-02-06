<?php

namespace App\Services\ExternalSystem\B2B;

use App\Entity\Order;
use App\Entity\Payment;
use App\Exception\ExternalSystemException;
use App\Exception\UCSException;
use App\Intf\PaymentNotifierInterface;
use App\Message\UCSPaymentNotification;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class B2BPaymentNotifier implements PaymentNotifierInterface
{
    public function __construct(private B2BService $b2BService,
                                private LoggerInterface $logger)
    {
    }

    public function support(Order $order, Payment $payment): bool
    {
        return true === $order->getUcsBillNeed();
    }

    public function notify(Order $order, Payment $payment): void
    {
        try {
            $this->b2BService->paymentNotify($order);
        } catch (ExternalSystemException $e) {
            $this->logger->error($e->getMessage(), ['orderId' => $order->getId()->toRfc4122(), 'exception' => $e]);
        }
    }
}
