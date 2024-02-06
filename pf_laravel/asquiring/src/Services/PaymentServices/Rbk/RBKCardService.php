<?php

namespace App\Services\PaymentServices\Rbk;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Enum\Status;
use App\Services\PaymentServices\Rbk\Dto\RBKRegisterParam;

class RBKCardService extends RBKBaseService
{
    public function getPaymentType(): string
    {
        return PaymentType::CARD;
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $this->em->persist($payment);

        $createInvoiceResult = $this->api->register($payment->getId(),
            new RBKRegisterParam($payment->getStringId(), $order->getIntAmount(), $order->getExpiresAt())
        );
        if (!$createInvoiceResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
            $this->em->flush();

            return $payment;
        }

        $payment
            ->setInvoiceId($createInvoiceResult->invoiceId)
            ->setPaidStatus(Status::PROCESS);
        $this->em->flush();

        return $payment;
    }
}
