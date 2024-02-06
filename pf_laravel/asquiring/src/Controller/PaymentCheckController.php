<?php

namespace App\Controller;

use App\Exception\PaymentException;
use App\Repository\PaymentRepository;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payments/check', name: 'payment_check', methods: 'get')]
class PaymentCheckController extends BaseController
{
    public function __construct(
        private OrderService $orderService,
        private PaymentRepository $paymentRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $payment = $this->paymentRepository->findOneBy(['invoiceId' => $request->query->get('orderId')]);

        try {
            $this->orderService->paymentCheck($payment);
        } catch (PaymentException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $this->json($payment);
    }
}
