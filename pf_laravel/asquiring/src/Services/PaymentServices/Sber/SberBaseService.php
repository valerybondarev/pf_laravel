<?php

namespace App\Services\PaymentServices\Sber;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\Provider;
use App\Enum\Status;
use App\Intf\OrderPayInterface;
use App\Services\PaymentServices\Sber\Dto\SberStatusParam;
use Doctrine\ORM\EntityManagerInterface;

abstract class SberBaseService implements OrderPayInterface
{
    public function __construct(
        protected SberApi $api,
        protected EntityManagerInterface $em
    ) {
    }

    public function getProvider(): string
    {
        return Provider::SBER;
    }

    abstract public function getPaymentType(): string;

    public function getProviderType(): string
    {
        return $this->getProvider() . '-' . $this->getPaymentType();
    }

    abstract public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment;

    public function checkPayment(Payment $payment): void
    {
        $result = $this->api->status($payment->getId(), new SberStatusParam($payment->getInvoiceId()));
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
