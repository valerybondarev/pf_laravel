<?php

namespace App\Services\PaymentServices\Rshb;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\ErrorCode;
use App\Enum\PaymentType;
use App\Enum\Status;
use App\Exception\PaymentException;
use App\Services\PaymentServices\Rshb\Dto\RshbRegisterParam;

class RshbCardService extends RshbBaseService
{
    public function getPaymentType(): string
    {
        return PaymentType::CARD;
    }

    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment
    {
        $payment = new Payment($order->getId(), $dto->provider, $dto->deviceType);
        $this->em->persist($payment);

        $param = new RshbRegisterParam($payment->getStringId(), $order->getIntAmount(), $order->getExpiresAt(),
            $order->getUcsBill(), $order->getPaymentNumber(), $order->getPaymentId());
        $registerResult = $this->api->register($order->getId(), $payment->getId(), $param);

        if (!$registerResult->isOk()) {
            $payment
                ->setInvoiceId(null)
                ->setPaymentUrl(null)
                ->setPaidStatus(Status::FAIL);
            $this->em->flush();

            throw PaymentException::fromErrorCode(ErrorCode::ERROR_CREATING_PAYMENT, $registerResult->errorMessage);
        }

        $payment
            ->setInvoiceId($registerResult->orderId)
            ->setPaymentUrl($registerResult->formUrl)
            ->setPaidStatus(Status::PROCESS);
        $this->em->flush();

        return $payment;
    }
}
