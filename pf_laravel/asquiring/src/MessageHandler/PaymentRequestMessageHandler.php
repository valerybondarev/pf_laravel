<?php

namespace App\MessageHandler;

use App\Entity\PaymentRequest;
use App\Message\PaymentRequestMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PaymentRequestMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(PaymentRequestMessage $message)
    {
        $paymentRequest = new PaymentRequest(
            $message->createdAt,
            $message->paymentId,
            $message->method,
            $message->url,
            $message->request,
            $message->id
        );
        $this->entityManager->persist($paymentRequest);
        $this->entityManager->flush();
    }
}
