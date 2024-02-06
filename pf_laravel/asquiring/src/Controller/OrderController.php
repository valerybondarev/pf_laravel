<?php

namespace App\Controller;

use App\Dto\Controller\ErrorDto;
use App\Dto\Controller\OrderCreateDto;
use App\Dto\Controller\OrderCreateResponseDto;
use App\Dto\Controller\OrderDeleteResponseDto;
use App\Dto\Controller\OrderDetailResponseDto;
use App\Dto\Controller\OrderPaymentCreateDto;
use App\Dto\Controller\OrderResponseDto;
use App\Dto\Controller\OrdersFilterDto;
use App\Dto\Controller\OrdersResponseDto;
use App\Dto\Controller\PaymentCreateResponseDto;
use App\Dto\Controller\PaymentsResponseDto;
use App\Dto\Controller\PaymentStatusResponseDto;
use App\Dto\Controller\ProductDto;
use App\Enum\ErrorCode;
use App\Exception\ExternalSystemException;
use App\Exception\PaymentException;
use App\Exception\UCSException;
use App\Services\EntityMapper;
use App\Services\OrderService;
use App\Services\ViolationsMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1', name: 'order_')]
class OrderController extends BaseController
{
    public function __construct(
        private ValidatorInterface $validator,
        private OrderService $orderService,
        private EntityMapper $mapper,
        private ViolationsMapper $violationsMapper
    ) {
    }

    /**
     * @OA\RequestBody(required=true, description="Параметры создания заказа", @Model(type=OrderCreateDto::class))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Возвращает созданный заказ",
     *     @Model(type=OrderCreateResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders', name: 'create', methods: 'post')]
    public function create(OrderCreateDto $dto): JsonResponse
    {
        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            return $this->validationError($this->violationsMapper->join($violations));
        }

        try {
            $order = $this->orderService->orderCreate($dto);
        } catch (UCSException|ExternalSystemException|PaymentException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $this->json($this->mapper->convertToOrderCreateResponseDto($order));
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Информация о заказе",
     *     @Model(type=OrderResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}', name: 'get', methods: 'get')]
    public function get(string $orderId): JsonResponse
    {
        $order = $this->orderService->getOrderAndCheckPayment($orderId);
        if (is_null($order)) {
            return $this->error(ErrorCode::ORDER_NOT_FOUND);
        }

        if (true === $order->getUcsBillNeed() && empty($order->getUcsBill())) {
            return $this->error(ErrorCode::CREATING_USC_BILL);
        }

        return $this->json($this->mapper->convertToOrderResponseDto($order));
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Детальная информация о заказе для страницы предварительнго просмотра",
     *     @Model(type=OrderDetailResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}/detail', name: 'get_detail', methods: 'get')]
    public function getDetail(string $orderId): JsonResponse
    {
        $order = $this->orderService->getOrderAndCheckPayment($orderId);
        if (is_null($order)) {
            return $this->error(ErrorCode::ORDER_NOT_FOUND);
        }

        if (true === $order->getUcsBillNeed() && empty($order->getUcsBill())) {
            return $this->error(ErrorCode::CREATING_USC_BILL);
        }

        try {
            $detail = $this->orderService->orderDetail($order);

            return $this->json($this->mapper->convertToOrderDetailResponseDto($order, $detail));
        } catch (PaymentException|ExternalSystemException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\RequestBody(required=true, description="Параметры создания платежа", @Model(type=OrderPaymentCreateDto::class))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Созданный платеж",
     *     @Model(type=PaymentCreateResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}/payments', name: 'payment_create', methods: 'post')]
    public function payments(string $orderId, OrderPaymentCreateDto $dto): JsonResponse
    {
        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            return $this->validationError($this->violationsMapper->join($violations));
        }

        try {
            $payment = $this->orderService->orderPay($orderId, $dto);

            return $this->json($this->mapper->convertToPaymentCreateResponseDto($payment));
        } catch (PaymentException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Статус платежа",
     *     @Model(type=PaymentStatusResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}/payments/status', name: 'payment_status', methods: 'get')]
    public function paymentStatus(string $orderId): JsonResponse
    {
        $payment = $this->orderService->activePaymentGet($orderId);
        if (is_null($payment)) {
            return $this->json(new ErrorDto(ErrorCode::ORDER_NOT_FOUND), Response::HTTP_NOT_FOUND);
        }

        try {
            $this->orderService->paymentCheck($payment);
        } catch (PaymentException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $this->json($this->mapper->convertToPaymentStatusResponseDto($payment));
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Список платежей указанного заказа",
     *     @Model(type=PaymentsResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}/payments', name: 'payments_get', methods: 'get')]
    public function paymentsGet(string $orderId): JsonResponse
    {
        $payments = $this->orderService->paymentsGet($orderId);
        if (is_null($payments)) {
            return $this->json(new ErrorDto(ErrorCode::ORDER_NOT_FOUND), Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->mapper->convertToPaymentsResponseDto($payments));
    }

    /**
     * @OA\Parameter(required=false, in="path", name="product", description="Наименование продукта (может быть несколько через запятую)", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="status", description="Cтатус записи (может быть несколько через запятую)", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="agent_id", description="uuid агента", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="client_id", description="uuid клиента", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="sort", description="Сортировка: product | status | created_at", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="sort_by", description="Порядок сортировки: ASC | DESC", @OA\Schema(type="string"))
     * @OA\Parameter(required=false, in="path", name="per_page", description="Число записей на странице, если не указано - 15", @OA\Schema(type="int"))
     * @OA\Parameter(required=false, in="path", name="page", description="Номер страницы", @OA\Schema(type="int"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Список заказов",
     *     @Model(type=OrdersResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders', name: 'orders_get', methods: 'get')]
    public function ordersGet(Request $request): JsonResponse
    {
        $pagingResult = $this->orderService->ordersGet(OrdersFilterDto::fromRequestParams($request->query));

        return $this->json($this->mapper->convertToOrdersResponseDto($pagingResult));
    }

    /**
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Созданный ордер",
     *     @Model(type=OrderCreateResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_NOT_FOUND, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/link', name: 'payment_link_get', methods: 'get')]
    public function getLink(Request $request): JsonResponse
    {
        $productDto = ProductDto::fromRequestParams($request->query);
        $violations = $this->validator->validate($productDto);
        if (count($violations)) {
            return $this->validationError($this->violationsMapper->join($violations));
        }

        $order = $this->orderService->orderByProduct($productDto);
        if (is_null($order)) {
            return $this->json(new ErrorDto(ErrorCode::ORDER_NOT_FOUND), Response::HTTP_NOT_FOUND);
        }

        if (true === $order->getUcsBillNeed() && empty($order->getUcsBill())) {
            return $this->error(ErrorCode::CREATING_USC_BILL, '', Response::HTTP_ACCEPTED);
        }

        try {
            $this->orderService->checkExpired($order);
        } catch (PaymentException) {
        }

        return $this->json($this->mapper->convertToOrderResponseDto($order));
    }

    /**
     * @OA\Parameter(required=true, in="path", name="orderId", description="Order Id", @OA\Schema(type="string"))
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Информация о заказе",
     *     @Model(type=OrderDeleteResponseDto::class)
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="В случае ошибки", @Model(type=ErrorDto::class))
     */
    #[Route('/orders/{orderId}', name: 'delete', methods: 'delete')]
    public function deleteOrder(string $orderId): JsonResponse
    {
        try {
            $order = $this->orderService->deleteOrder($orderId);

            return $this->json($this->mapper->convertToOrderDeleteResponseDto($order));
        } catch (PaymentException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}
