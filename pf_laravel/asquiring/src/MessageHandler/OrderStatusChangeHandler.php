<?php

namespace App\MessageHandler;

use App\Enum\Status;
use App\Message\OrderStatusChange;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderStatusChangeHandler implements MessageHandlerInterface
{
    public function __construct(
        private OrderService $orderService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(OrderStatusChange $message)
    {
        if ($order = $this->orderService->orderGetByUcsInvoiceId($message->ucsInvoiceId)) {
            if ($message->isStatusVerified()) {
                if ($order->isSuccessful()) {
                    $order->setStatus(Status::VERIFIED);
                    $this->entityManager->flush();
                }
            }
        }
    }
}
