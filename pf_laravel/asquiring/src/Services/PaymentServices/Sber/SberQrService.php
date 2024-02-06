<?php

namespace App\Services\PaymentServices\Sber;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Enum\Status;
use App\Services\PaymentServices\Sber\Dto\SberQrGetParam;
use App\Services\PaymentServices\Sber\Dto\SberRegisterParam;

class SberQrService extends SberBaseService
{
    public function getPaymentType(): string
    {
        return PaymentType::QR;
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $this->em->persist($payment);

        $param = new SberRegisterParam($payment->getStringId(), $order->getIntAmount(), $order->getExpiresAt(),
            $order->getPaymentDescription(), $order->getPaymentNumber(), $order->getPaymentId());
        $registerResult = $this->api->register($order->getId(), $payment->getId(), $param);
        if (!$registerResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
            $this->em->flush();

            return $payment;
        }

        $payment->setInvoiceId($registerResult->orderId);
        $qrGetResult = $this->api->qrGet($payment->getId(), new SberQrGetParam($registerResult->orderId));
        if (!$qrGetResult->isOk()) {
            $payment->setPaidStatus(Status::FAIL);
            $this->em->flush();

            return $payment;
        }

        $payment
            ->setQrId($qrGetResult->qrId)
            ->setPaymentUrl($qrGetResult->payload)
            ->setPaidStatus(Status::PROCESS);
        $this->em->flush();

        return $payment;
    }
}
