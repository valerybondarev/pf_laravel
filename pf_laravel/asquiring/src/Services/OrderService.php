<?php

namespace App\Services;

use App\Dto\Controller\Contractor;
use App\Dto\Controller\OrderCreateDto;
use App\Dto\Controller\OrderPaymentCreateDto;
use App\Dto\Controller\OrdersFilterDto;
use App\Dto\Controller\PaymentBaseDto;
use App\Dto\Controller\ProductDto;
use App\Dto\PagingResult;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\PaymentBase;
use App\Enum\ErrorCode;
use App\Enum\Status;
use App\Exception\ExternalSystemException;
use App\Exception\PaymentException;
use App\Exception\UCSException;
use App\Intf\OrderDetailInterface;
use App\Intf\OrderPayInterface;
use App\Message\SuccessPaymentNotification;
use App\Message\VisitedMessage;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Services\ExternalSystem\UCS\UCSService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

class OrderService
{
    public function __construct(
        private iterable $orderPayServices,
        private iterable $orderDetailServices,
        private EntityManagerInterface $em,
        private OrderRepository $orderRepository,
        private PaymentRepository $paymentRepository,
        private MessageBusInterface $bus,
        private UCSService $ucsService,
        private DenormalizerInterface $denormalizer,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws PaymentException
     */
    private function getPayService(string $provider): OrderPayInterface
    {
        foreach ($this->orderPayServices as $service) {
            if ($service instanceof OrderPayInterface) {
                if ($provider === $service->getProviderType()) {
                    return $service;
                }
            }
        }

        throw PaymentException::fromErrorCode(ErrorCode::UNKNOWN_PROVIDER, $provider);
    }

    /**
     * @throws PaymentException
     */
    private function getDetailService(string $source): OrderDetailInterface
    {
        foreach ($this->orderDetailServices as $service) {
            if ($service instanceof OrderDetailInterface) {
                if ($service->supports($source)) {
                    return $service;
                }
            }
        }

        throw PaymentException::fromErrorCode(ErrorCode::UNKNOWN_SOURCE, $source);
    }

    /**
     * @throws PaymentException
     */
    public function checkExpired(Order $order): void
    {
        if ($order->isExpired()) {
            if (!$order->isSuccess() && !$order->isFail()) {
                $order->setStatus(Status::FAIL);
                $this->em->flush();
            }

            throw PaymentException::fromErrorCode(ErrorCode::ORDER_EXPIRED, $order->getId());
        }
    }

    /**
     * @throws PaymentException
     */
    private function checkDeleted(Order $order): void
    {
        if (!empty($order->getDeletedAt())) {
            throw PaymentException::fromErrorCode(ErrorCode::ORDER_DELETED, $order->getId());
        }
    }

    /**
     * @throws PaymentException
     * @throws ExternalSystemException
     * @throws UCSException
     */
    public function orderCreate(OrderCreateDto $dto): Order
    {
        $amount = array_reduce($dto->paymentBasis,
            function (float $acc, PaymentBaseDto $e) {
                return $acc + $e->amount;
            }, 0);
        $order = new Order($dto->source, $amount, $dto->agentId, $dto->clientId, $dto->expiresAt, $dto->ucsBillNeed,
            $dto->contractor, $dto->returnUrl, $dto->type);
        array_walk($dto->paymentBasis, function ($e) use ($order) {
            /* @var PaymentBaseDto $e */
            $order->addPaymentBasis(new PaymentBase($e->unitId, $e->product, $e->amount, $e->paymentNumber, $e->payNumber));
        });
        $this->em->persist($order);
        $this->em->flush();

        if ($dto->ucsBillNeed) {
            try {
                $order = $this->createUcsInvoice($order);
            } catch (ExternalSystemException|UCSException|PaymentException $e) {
                $order->setDeletedAt(new \DateTimeImmutable());
                $this->em->flush();

                throw $e;
            }
        }

        return $order;
    }

    public function getOrderAndCheckPayment(string $orderId): ?Order
    {
        $this->visit($orderId);

        $this->checkActivePayment($orderId);

        return $this->getOrder($orderId);
    }

    private function visit(string $orderId): void
    {
        $uuid = Uuid::fromString($orderId);
        $createdAt = new \DateTimeImmutable();
        $this->bus->dispatch(new VisitedMessage($createdAt, $uuid, $createdAt));
    }

    private function checkActivePayment(string $orderId): void
    {
        $payment = $this->activePaymentGet($orderId);
        if (!is_null($payment)) {
            try {
                $this->paymentCheck($payment);
            } catch (PaymentException) {
            }
        }
    }

    public function getOrder(string $orderId): ?Order
    {
        $uuid = Uuid::fromString($orderId);

        return $this->orderRepository->find($uuid);
    }

    public function orderGetByUcsInvoiceId(string $ucsInvoiceId): ?Order
    {
        return $this->orderRepository->findOneBy(['ucsInvoiceId' => $ucsInvoiceId]);
    }

    /**
     * @throws PaymentException
     * @throws ExternalSystemException
     */
    public function orderDetail(Order $order): ?array
    {
        $this->checkDeleted($order);

        return $this->getDetailService($order->getSource())->getDetail($order);
    }

    /**
     * @throws PaymentException
     */
    public function orderPay(string $orderId, OrderPaymentCreateDto $dto): ?Payment
    {
        $order = $this->getOrder($orderId);
        if (is_null($order)) {
            throw PaymentException::fromErrorCode(ErrorCode::ORDER_NOT_FOUND, $orderId);
        }

        if ($order->isSuccess()) {
            throw PaymentException::fromErrorCode(ErrorCode::ORDER_PAYED, $orderId);
        }

        $activePayment = $this->activePaymentGet($orderId);
        if (!is_null($activePayment)) {
            $this->paymentCheck($activePayment);
            if ($activePayment->isSuccess()) {
                $order->setStatus(Status::SUCCESS);
                $this->em->flush();

                throw PaymentException::fromErrorCode(ErrorCode::ORDER_PAYED, $orderId);
            }
        }

        $this->checkDeleted($order);
        $this->checkExpired($order);

        if (!is_null($activePayment) && $activePayment->isProcess()) {
            if ($activePayment->getProviderType() === $dto->provider) {
                return $activePayment;
            }
        }

        return $this->getPayService($dto->provider)->createPayment($order, $dto);
    }

    /**
     * @throws ExternalSystemException
     * @throws PaymentException
     * @throws UCSException
     */
    public function createUcsInvoice(Order $order): Order
    {
        $this->logger->info('[createUcsInvoice] called', ['order' => $order->getId()]);
        if (empty($order->getContractor())) {
            $this->logger->info('[createUcsInvoice] getting contractor', ['order' => $order->getId()]);

            try {
                $contractor = $this->getDetailService($order->getSource())->getContractor($order);
                $order->setContractor($this->denormalizer->denormalize($contractor, Contractor::class));
                $this->em->flush();
            } catch (PaymentException|ExternalSystemException $e) {
                $this->logger->error('[createUcsInvoice] error getting contractor', ['error' => $e, 'order' => $order->getId()]);

                throw $e;
            }
        }

        $ucsSerializer = UCSSerializer::create([
            'inn' => 'INN',
            'kpp' => 'KPP',
            'okpo' => 'OKPO',
        ]);
        $contractor = $ucsSerializer->normalize($order->getContractor());

        $this->logger->info('[createUcsInvoice] creating invoice', ['order' => $order->getId(), 'contractor' => $contractor]);

        try {
            $order = $this->ucsService->createInvoice($order, $contractor);
            $this->logger->info('[createUcsInvoice] created invoice', ['order' => $order->getId(), 'ucsBill' => $order->getUcsBill()]);

            return $order;
        } catch (UCSException $e) {
            $this->logger->error('[createUcsInvoice] error creating invoice', ['order' => $order->getId()]);

            throw $e;
        }
    }

    /**
     * @throws PaymentException
     */
    public function paymentCheck(Payment $payment, bool $checkOnly = false): void
    {
        if ($payment->isProcess()) {
            $this->getPayService($payment->getProviderType())->checkPayment($payment);
            if ($checkOnly) {
                return;
            }

            if ($payment->isSuccess() || $payment->isFail()) {
                $order = $this->getOrder($payment->getOrderId()->toRfc4122());
                $order->setStatus($payment->getPaidStatus());
                $this->em->flush();

                $this->bus->dispatch(new SuccessPaymentNotification($order->getId()->toRfc4122()));
            }
        }
    }

    public function activePaymentGet(string $orderId): ?Payment
    {
        return $this->paymentRepository->findOneBy(
            ['orderId' => $orderId],
            ['createdAt' => 'desc', 'id' => 'desc']
        );
    }

    public function paymentsGet(string $orderId): ?array
    {
        $order = $this->getOrder($orderId);
        if (is_null($order)) {
            return null;
        }

        return $this->paymentRepository->findBy(['orderId' => $orderId]);
    }

    public function ordersGet(OrdersFilterDto $params): PagingResult
    {
        return $this->orderRepository->getWithPaging($params);
    }

    public function orderByProduct(ProductDto $dto): ?Order
    {
        return $this->orderRepository->getByProduct($dto);
    }

    /**
     * @throws PaymentException
     */
    public function deleteOrder(string $orderId): ?Order
    {
        $order = $this->getOrder($orderId);
        if (is_null($order)) {
            throw PaymentException::fromErrorCode(ErrorCode::ORDER_NOT_FOUND, $orderId);
        }

        $this->checkActivePayment($orderId);
        if ($order->isSuccess()) {
            throw PaymentException::fromErrorCode(ErrorCode::ORDER_PAYED, $orderId);
        }

        if (is_null($order->getDeletedAt())) {
            $order->setDeletedAt(new \DateTimeImmutable());
            $this->em->flush();
        }

        return $order;
    }
}
