<?php

namespace App\Services;

use App\Entity\Payment;
use App\Enum\Status;
use App\Exception\PaymentException;
use App\Message\SuccessPaymentNotification;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PaymentCheckService
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaymentRepository $paymentRepository,
        private OrderService $orderService,
        private MessageBusInterface $bus
    ) {
    }

    public function paymentsCheck(): int
    {
        $payments = $this->paymentRepository->findBy(['paidStatus' => 'process']);

        $orders = array_map(
            function (Payment $payment) { return $payment->getOrderId()->toRfc4122(); },
            $payments
        );
        $orders = array_unique($orders);

        foreach ($orders as $orderId) {
            $orderPayments = array_filter($payments, function (Payment $payment) use ($orderId) {
                return $orderId === $payment->getOrderId()->toRfc4122();
            });
            $this->orderPaymentsCheck($orderId, $orderPayments);
        }

        return count($payments);
    }

    /**
     * @param Payment[] $payments
     */
    private function orderPaymentsCheck(string $orderId, array $payments): void
    {
        $isSuccess = false;
        $isFail = false;
        $isProcess = false;

        foreach ($payments as $payment) {
            try {
                $this->orderService->paymentCheck($payment, true);
            } catch (PaymentException) {
            }
            if ($payment->isSuccess()) {
                $isSuccess = true;
            }
            if ($payment->isFail()) {
                $isFail = true;
            }
            if ($payment->isProcess()) {
                $isProcess = true;
            }
        }

        if ($isSuccess || ($isFail && !$isProcess)) {
            $order = $this->orderService->getOrder($orderId);
            if (!$order->isSuccess()) {
                $order->setStatus($isSuccess ? Status::SUCCESS : Status::FAIL);
                $this->em->flush();

                $this->bus->dispatch(new SuccessPaymentNotification($orderId));
            }
        }
    }
}
