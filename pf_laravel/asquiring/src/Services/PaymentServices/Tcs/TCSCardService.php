<?php

namespace App\Services\PaymentServices\Tcs;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Enum\Status;
use App\Services\PaymentServices\Tcs\Dto\TCSRegisterParam;

class TCSCardService extends TCSBaseService
{
    public function getPaymentType(): string
    {
        return PaymentType::CARD;
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $this->em->persist($payment);

        $registerResult = $this->api->register($order->getId(), $payment->getId(),
            new TCSRegisterParam($payment->getStringId(), $order->getIntAmount(), $order->getExpiresAt())
        );

        if (!$registerResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
        } else {
            $payment
                ->setInvoiceId($registerResult->orderId)
                ->setPaymentUrl($registerResult->formUrl)
                ->setPaidStatus(Status::PROCESS);
        }
        $this->em->flush();

        return $payment;
    }
}
