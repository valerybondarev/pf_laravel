<?php

namespace App\Services\ExternalSystem\B2B;

use App\Entity\Order;
use App\Enum\ErrorCode;
use App\Exception\ExternalSystemException;
use App\Intf\OrderDetailInterface;
use App\Services\ExternalSystem\B2B\Dto\InfoParam;
use App\Services\ExternalSystem\B2B\Dto\PaymentNotifyParam;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class B2BService implements OrderDetailInterface
{
    public function __construct(
        protected B2BApi $api,
        protected EntityManagerInterface $em,
        private array $supportedSources
    ) {
    }

    public function supports(string $source): bool
    {
        return in_array($source, $this->supportedSources);
    }

    /**
     * @throws ExternalSystemException
     */
    #[ArrayShape(['data' => "mixed"])]
    public function paymentNotify(Order $order): array
    {
        $param = new PaymentNotifyParam($this->getProductsArray($order));

        $result = $this->api->paymentNotify($param);
        if ($result->isOk()) {
            return [
                'data' => $result->data,
            ];
        }
        throw ExternalSystemException::fromErrorCode(ErrorCode::ERROR_PAYMENT_NOTIFICATION, $result->errorMessage);
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
        $param = new InfoParam($this->getProductsArray($order));

        $result = $this->api->getContractor($param);
        if ($result->isOk()) {
            return $result->data;
        }

        throw ExternalSystemException::fromErrorCode(ErrorCode::ERROR_GET_CONTRACTOR, $result->errorMessage);
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
