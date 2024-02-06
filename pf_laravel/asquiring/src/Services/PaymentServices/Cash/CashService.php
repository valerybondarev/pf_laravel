<?php

namespace App\Services\PaymentServices\Cash;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\Provider;
use App\Enum\Status;
use App\Intf\OrderPayInterface;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;

class CashService implements OrderPayInterface
{
    public function __construct(
        protected EntityManagerInterface $em
    ) {
    }

    public function getProvider(): string
    {
        return Provider::CASH;
    }

    public function getPaymentType(): string
    {
        return '';
    }

    #[Pure]
    public function getProviderType(): string
    {
        return $this->getProvider();
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $payment->setPaidAt(new \DateTimeImmutable());
        $payment->setPaidStatus(Status::SUCCESS);
        $payment->setInvoiceId('');
        $payment->setPaymentUrl('');
        $this->em->persist($payment);
        $this->em->flush();

        return $payment;
    }

    public function checkPayment(Payment $payment): void
    {
    }
}
