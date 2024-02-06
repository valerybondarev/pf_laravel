<?php

namespace App\Services\PaymentServices\Rshb;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\Provider;
use App\Enum\Status;
use App\Intf\OrderPayInterface;
use App\Services\PaymentServices\Rshb\Dto\RshbStatusParam;
use Doctrine\ORM\EntityManagerInterface;

abstract class RshbBaseService implements OrderPayInterface
{
    public function __construct(
        protected RshbApi $api,
        protected EntityManagerInterface $em
    ) {
    }

    public function getProvider(): string
    {
        return Provider::RSHB;
    }

    abstract public function getPaymentType(): string;

    public function getProviderType(): string
    {
        return $this->getProvider() . '-' . $this->getPaymentType();
    }

    abstract public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment;

    public function checkPayment(Payment $payment): void
    {
        $result = $this->api->status($payment->getId(), new RshbStatusParam($payment->getInvoiceId()));
        if ($result->isSuccess()) {
            $payment
                ->setType($result->payedType)
                ->setPaidStatus(Status::SUCCESS)
                ->setPaidAt($result->payedAt);
        } elseif ($result->isFail()) {
            $payment
                ->setType($result->payedType)
                ->setPaidStatus(Status::FAIL);
        }
        $this->em->flush();
    }
}
