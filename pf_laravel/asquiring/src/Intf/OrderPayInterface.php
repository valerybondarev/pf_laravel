<?php

namespace App\Intf;

use App\Dto\Controller\OrderPaymentCreateDto;
use App\Entity\Order;
use App\Entity\Payment;
use App\Exception\PaymentException;

interface OrderPayInterface
{
    public function getProvider(): string;

    public function getPaymentType(): string;

    public function getProviderType(): string;

    /**
     * @throws PaymentException
     */
    public function createPayment(Order $order, OrderPaymentCreateDto $dto): Payment;

    public function checkPayment(Payment $payment): void;
}
