<?php

namespace App\Services\PaymentServices\Tcs;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Enum\Status;
use App\Services\PaymentServices\Tcs\Dto\TCSQrGetParam;
use App\Services\PaymentServices\Tcs\Dto\TCSRegisterParam;

class TCSQrService extends TCSBaseService
{
    public function getPaymentType(): string
    {
        return PaymentType::QR;
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $this->em->persist($payment);

        $registerResult = $this->api->register($order->getId(), $payment->getId(),
            new TCSRegisterParam($payment->getStringId(), $order->getIntAmount(), $order->getExpiresAt()));
        if (!$registerResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
            $this->em->flush();

            return $payment;
        }

        $payment->setInvoiceId($registerResult->orderId);
        $qrGetResult = $this->api->qrGet($payment->getId(), new TCSQrGetParam($registerResult->orderId));
        if (!$qrGetResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
            $this->em->flush();

            return $payment;
        }

        $payment
            ->setPaymentUrl($qrGetResult->payload)
            ->setPaidStatus(Status::PROCESS);
        $this->em->flush();

        return $payment;
    }
}
