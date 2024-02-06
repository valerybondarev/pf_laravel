<?php

namespace App\Controller;

use App\Dto\Controller\ErrorDto;
use App\Enum\ErrorCode;
use App\Enum\Status;
use App\MercureEvents\Event;
use App\MercureEvents\Type;
use App\Repository\PaymentRepository;
use App\Services\OrderService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/payment-callback', name: 'payment_callback', methods: 'get')]
class PaymentCallbackController extends BaseController
{
    /**
     * @param PaymentRepository $paymentRepository
     * @param LoggerInterface $logger
     * @param OrderService $orderService
     * @param Event $event
     */
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private LoggerInterface $logger,
        private OrderService $orderService,
        private readonly Event $event
    ) {
    }

    /**
     * Метод для обработки уведомления от платежного шлюза по заказу и передачи данных в топик Mercure.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        try {
            $mdOrder = $request->query->get('mdOrder');
            $payment = $this->paymentRepository->findOneBy(['invoiceId' => $mdOrder]);

            if (!$payment) {
                return $this->json(new ErrorDto(ErrorCode::PAYMENT_NOT_FOUND), Response::HTTP_NOT_FOUND);
            }

            $this->orderService->paymentCheck($payment);

            $this->event->publish(Type::ORDER_STATUS_HAS_BEEN_CHANGED, $payment->getId()->toRfc4122(), [
                'order_id' => $payment->getOrderId()->toRfc4122(),
                'payment_id' => $payment->getId()->toRfc4122(),
                'paid_status' => $payment->isSuccess() ? Status::SUCCESS : Status::FAIL,
            ]);

            return $this->json([]);
        } catch (Exception $exception) {
            $this->logger->error("{$exception->getMessage()} {$exception->getTraceAsString()}");

            return $this->error($exception->getCode(), $exception->getMessage());
        }
    }
}
