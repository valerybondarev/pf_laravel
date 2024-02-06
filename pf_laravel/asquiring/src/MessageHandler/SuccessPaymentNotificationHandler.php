<?php

namespace App\MessageHandler;

use App\Intf\PaymentNotifierInterface;
use App\Message\SuccessPaymentNotification;
use App\Services\OrderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SuccessPaymentNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private iterable $notifiers,
        private OrderService $orderService,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(SuccessPaymentNotification $message)
    {
        $order = $this->orderService->getOrder($message->orderId);
        $payment = $this->orderService->activePaymentGet($message->orderId);

        if ($order && $payment) {
            foreach ($this->notifiers as $notifier) {
                if ($notifier instanceof PaymentNotifierInterface) {
                    if ($notifier->support($order, $payment)) {
                        $this->logger->info('Sending payment notification', [
                            'notifier' => $notifier::class,
                            'message' => $message,
                        ]);

                        $notifier->notify($order, $payment);
                    } else {
                        $this->logger->info('Not supported, skipped.', [
                            'notifier' => $notifier::class,
                            'message' => $message,
                        ]);
                    }
                }
            }
        }
    }
}
