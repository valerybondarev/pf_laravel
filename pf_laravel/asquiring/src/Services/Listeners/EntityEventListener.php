<?php

namespace App\Services\Listeners;

use App\Entity\Payment;
use App\Message\PaymentHistoryMessage;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class EntityEventListener
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Payment) {
            $changeSet = $args->getEntityChangeSet();
            $paymentHistoryMessage = new PaymentHistoryMessage(new \DateTimeImmutable(), $entity->getId(), $changeSet);
            $this->bus->dispatch($paymentHistoryMessage);
        }
    }
}
