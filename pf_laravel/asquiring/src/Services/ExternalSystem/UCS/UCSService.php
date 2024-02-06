<?php

namespace App\Services\ExternalSystem\UCS;

use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\ErrorCode;
use App\Enum\Status;
use App\Exception\ExternalSystemException;
use App\Exception\UCSException;
use App\Intf\OrderDetailInterface;
use App\Services\ExternalSystem\UCS\Dto\InfoParam;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class UCSService implements OrderDetailInterface
{
    public function __construct(
        protected UCSApi $api,
        protected EntityManagerInterface $em,
        private array $supportedSources
    ) {
    }

    /**
     * @throws UCSException
     */
    public function createInvoice(Order $order, array $contractor): ?Order
    {
        $products = [];

        foreach ($order->getPaymentBasis() as $product) {
            $products[] = [
                'DocId' => $product->getUnitId(),
                'Summ' => $product->getAmount(),
                'Stage' => $product->getPaymentNumber(),
                'PayNumber' => $product->getPayNumber(),
            ];
        }
        if (!is_null($order->getExpiresAt())) {
            $dueDate = \DateTimeImmutable::createFromInterface($order->getExpiresAt())
                ->add(new \DateInterval('P1D'))
                ->format('Y-m-d');
        } else {
            $dueDate = null;
        }

        $param = [
            'CreationType' => 'DataStructure',
            'DocumentBase' => $order->getBaseDocument(),
            'Date' => $order->getCreatedAt()->format('Y-m-d\TH:i:s'),
            'Organization' => '000000001',
            'InsuranceCompany' => '000000002',
            'Сontractor' => $contractor,
            'Currency' => 'RUB',
            'DueDate' => $dueDate,
            'Payments' => $products,
        ];

        $result = $this->api->createInvoice($param);
        if ($result->isOk()) {
            $order->setUcsBill($result->number);
            $order->setUcsInvoiceId($result->id);
            $order->setUcsCreatedAt(new \DateTimeImmutable());
            $order->setTotalAmount($result->amount);
            $this->em->flush();

            return $order;
        }

        throw UCSException::fromErrorCode(ErrorCode::ERROR_CREATING_USC_BILL, $result->errorMessage);
    }

    /**
     * @throws UCSException
     */
    public function successPaymentNotification(Order $order, Payment $payment): void
    {
        $statusMap = [Status::SUCCESS => true, Status::FAIL => false];
        $newPaidStatus = $statusMap[$order->getStatus()] ?? null;

        if (null !== $newPaidStatus && $order->getUcsInvoiceId()) {
            $param = [
                'InvoiceID' => $order->getUcsInvoiceId(),
                'PayConfirmed' => $newPaidStatus,
                'PaymentSystemId' => $payment->getProviderType(),
                'TransactionID' => $payment->getTransactionId(),
            ];

            $result = $this->api->successPaymentNotification($param);
            if ($result->isOk()) {
                return;
            }

            throw UCSException::fromErrorCode(ErrorCode::ERROR_PAYMENT_NOTIFICATION, $result->errorMessage);
        }
    }

    public function supports(string $source): bool
    {
        return in_array($source, $this->supportedSources);
    }

    #[ArrayShape(['title' => 'null|string', 'data' => 'array|null'])]
    public function getDetail(Order $order): ?array
    {
        $param = new InfoParam($this->getProductsArray($order));

        $result = $this->api->info($param);
        if ($result->isOk()) {
            return [
                'title' => $result->title,
                'data' => $result->data,
            ];
        }

        $order->setDeletedAt(new \DateTimeImmutable());
        $this->em->flush();

        throw ExternalSystemException::fromErrorCode(ErrorCode::ERROR_GET_DETAIL, $result->errorMessage);
    }

    public function getContractor(Order $order): ?array
    {
        return null;
    }

    #[Pure]
    private function getProductsArray(Order $order): array
    {
        $products = [];

        foreach ($order->getPaymentBasis() as $product) {
            $products[] = [
                'id' => $product->getUnitId(),
                'product' => $product->getProduct(),
            ];
        }

        return $products;
    }
}
