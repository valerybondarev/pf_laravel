<?php

namespace App\MessageHandler;

use App\Entity\PaymentResponse;
use App\Message\PaymentResponseMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PaymentResponseMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(PaymentResponseMessage $message)
    {
        $paymentResponse = new PaymentResponse(
            $message->createdAt,
            $message->requestId,
            $message->response,
            $message->success,
            $message->errorCode,
            $message->errorMessage,
            null
        );
        $this->entityManager->persist($paymentResponse);
        $this->entityManager->flush();
    }
}
