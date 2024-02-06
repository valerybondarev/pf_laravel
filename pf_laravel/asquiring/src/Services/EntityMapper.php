<?php

namespace App\Services;

use App\Dto\Controller\OrderCreateResponseDto;
use App\Dto\Controller\OrderDeleteResponseDto;
use App\Dto\Controller\OrderDetailResponseDto;
use App\Dto\Controller\OrderResponseDto;
use App\Dto\Controller\OrdersResponseDto;
use App\Dto\Controller\PaymentBaseDto;
use App\Dto\Controller\PaymentCreateResponseDto;
use App\Dto\Controller\PaymentLinkResponseDto;
use App\Dto\Controller\PaymentResponseDto;
use App\Dto\Controller\PaymentsResponseDto;
use App\Dto\Controller\PaymentStatusResponseDto;
use App\Dto\Controller\UcsInvoiceResponseDto;
use App\Dto\PagingResult;
use App\Entity\Order;
use App\Entity\Payment;
use JetBrains\PhpStorm\Pure;

class EntityMapper
{
    public function __construct(private PaymentTypesResolver $paymentTypesResolver)
    {
    }

    #[Pure]
    public function convertToOrderCreateResponseDto(Order $entity): OrderCreateResponseDto
    {
        $dto = new OrderCreateResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->status = $entity->getStatus();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->expiresAt = $entity->getExpiresAt();

        return $dto;
    }

    #[Pure]
    public function convertToOrderResponseDto(Order $entity): OrderResponseDto
    {
        $dto = new OrderResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->totalAmount = $entity->getTotalAmount();
        $dto->clientId = $entity->getClientId();
        $dto->agentId = $entity->getAgentId();
        $dto->status = $entity->getStatus();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->expiresAt = $entity->getExpiresAt();
        $dto->source = $entity->getSource();
        $dto->deletedAt = $entity->getDeletedAt();
        $dto->ucsBill = $entity->getUcsBill();
        $dto->ucsInvoiceId = $entity->getUcsInvoiceId();
        $dto->ucsCreatedAt = $entity->getUcsCreatedAt();
        $dto->contractorName = $entity->getContractorName();
        $dto->returnUrl = $entity->getReturnUrl();

        foreach ($entity->getPaymentBasis() as $product) {
            $paymentBase = new PaymentBaseDto();
            $paymentBase->product = $product->getProduct();
            $paymentBase->amount = $product->getAmount();
            $paymentBase->unitId = $product->getUnitId();
            $paymentBase->paymentNumber = $product->getPaymentNumber();
            $paymentBase->payNumber = $product->getPayNumber();
            $dto->paymentBasis[] = $paymentBase;
        }

        return $dto;
    }

    public function convertToUcsInvoiceResponseDto(Order $entity): UcsInvoiceResponseDto
    {
        $dto = new UcsInvoiceResponseDto();
        $dto->invoiceId = $entity->getUcsInvoiceId();
        $dto->invoiceNumber = $entity->getUcsBill();
        $dto->createdAt = $entity->getUcsCreatedAt();

        return $dto;
    }

    #[Pure]
    public function convertToPaymentCreateResponseDto(Payment $entity): PaymentCreateResponseDto
    {
        $dto = new PaymentCreateResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->invoiceId = $entity->getInvoiceId();
        $dto->paymentUrl = $entity->getPaymentUrl();
        $dto->createdAt = $entity->getCreatedAt();

        return $dto;
    }

    #[Pure]
    public function convertToPaymentStatusResponseDto(Payment $entity): PaymentStatusResponseDto
    {
        $dto = new PaymentStatusResponseDto();
        $dto->orderId = $entity->getOrderId()->toRfc4122();
        $dto->paymentId = $entity->getId()->toRfc4122();
        $dto->paidStatus = $entity->getPaidStatus();

        return $dto;
    }

    #[Pure]
    public function convertToPaymentResponseDto(Payment $entity): PaymentResponseDto
    {
        $dto = new PaymentResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->orderId = $entity->getOrderId()->toRfc4122();
        $dto->provider = $entity->getProviderType();
        $dto->deviceType = $entity->getDeviceType();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->invoiceId = $entity->getInvoiceId();
        $dto->qrId = $entity->getQrId();
        $dto->paymentUrl = $entity->getPaymentUrl();
        $dto->paidAt = $entity->getPaidAt();
        $dto->paidStatus = $entity->getPaidStatus();
        $dto->transactionId = $entity->getTransactionId();
        $dto->verifiedAt = $entity->getVerifiedAt();
        $dto->verifyStatus = $entity->getVerifyStatus();

        return $dto;
    }

    #[Pure]
    public function convertToPaymentLinkResponseDto(Payment $entity): PaymentLinkResponseDto
    {
        $dto = new PaymentLinkResponseDto();
        $dto->paymentUrl = $entity->getPaymentUrl();

        return $dto;
    }

    #[Pure]
    public function convertToPaymentsResponseDto(array $payments): PaymentsResponseDto
    {
        $data = array_map(
            function ($e) {
                return $this->convertToPaymentResponseDto($e);
            }, $payments);
        $dto = new PaymentsResponseDto();
        $dto->data = $data;
        $dto->total = count($data);

        return $dto;
    }

    public function convertToOrdersResponseDto(PagingResult $pagingResult): OrdersResponseDto
    {
        $data = array_map(
            function ($e) {
                $item = (array) $this->convertToOrderResponseDto($e);
                unset($item['success']);

                return $item;
            }, $pagingResult->data);
        $dto = new OrdersResponseDto();
        $dto->data = $data;
        $dto->page = $pagingResult->page;
        $dto->perPage = $pagingResult->perPage;
        $dto->total = $pagingResult->total;

        return $dto;
    }

    public function convertToOrderDetailResponseDto(Order $entity, array $detail): OrderDetailResponseDto
    {
        $dto = new OrderDetailResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->status = $entity->getStatus();
        $dto->type = $entity->getType();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->amount = $entity->getTotalAmount();
        $dto->detail = $detail;
        $dto->returnUrl = $entity->getReturnUrl();
        $dto->expiresAt = $entity->getExpiresAt();
        $dto->paymentTypes = $this->paymentTypesResolver->getPaymentTypes($entity->getSource());

        return $dto;
    }

    public function convertToOrderDeleteResponseDto(Order $entity): OrderDeleteResponseDto
    {
        $dto = new OrderDeleteResponseDto();
        $dto->id = $entity->getId()->toRfc4122();
        $dto->status = $entity->getStatus();

        return $dto;
    }
}
