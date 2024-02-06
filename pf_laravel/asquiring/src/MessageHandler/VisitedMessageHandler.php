<?php

namespace App\MessageHandler;

use App\Entity\Visited;
use App\Enum\Status;
use App\Message\VisitedMessage;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class VisitedMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository
    ) {
    }

    public function __invoke(VisitedMessage $message)
    {
        $order = $this->orderRepository->find($message->orderId);
        if (!is_null($order)) {
            if (Status::CREATED === $order->getStatus()) {
                $order->setStatus(Status::VISITED);
            }

            $visited = new Visited(
                new \DateTimeImmutable(),
                $message->orderId,
                $message->visitedAt,
                null);
            $this->entityManager->persist($visited);
            $this->entityManager->flush();
        }
    }
}
