<?php

namespace App\Services\PaymentServices\Rbk;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\Provider;
use App\Enum\Status;
use App\Intf\OrderPayInterface;
use App\Services\PaymentServices\Rbk\Dto\RBKStatusParam;
use Doctrine\ORM\EntityManagerInterface;

abstract class RBKBaseService implements OrderPayInterface
{
    public function __construct(
        protected RBKApi $api,
        protected EntityManagerInterface $em
    ) {
    }

    public function getProvider(): string
    {
        return Provider::RBK;
    }

    abstract public function getPaymentType(): string;

    public function getProviderType(): string
    {
        return $this->getProvider() . '-' . $this->getPaymentType();
    }

    abstract public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment;

    public function checkPayment(Payment $payment): void
    {
        $result = $this->api->status($payment->getId(), new RBKStatusParam($payment->getInvoiceId()));
        if ($result->isOk()) {
            $payment
                ->setPaidStatus(Status::SUCCESS)
                ->setPaidAt($result->payedAt);
        } else {
            $payment
                ->setPaidStatus(Status::FAIL);
        }
        $this->em->flush();
    }
}
