<?php

namespace App\MessageHandler;

use App\Entity\PaymentHistory;
use App\Message\PaymentHistoryMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PaymentHistoryMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(PaymentHistoryMessage $message)
    {
        $paymentHistory = new PaymentHistory(
            $message->createdAt,
            $message->paymentId,
            $message->payload,
            null
        );
        $this->entityManager->persist($paymentHistory);
        $this->entityManager->flush();
    }
}
