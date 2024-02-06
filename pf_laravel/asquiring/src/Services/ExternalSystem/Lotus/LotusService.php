<?php

namespace App\Services\ExternalSystem\Lotus;

use App\Entity\Order;
use App\Enum\ErrorCode;
use App\Exception\ExternalSystemException;
use App\Intf\OrderDetailInterface;
use App\Services\ExternalSystem\Lotus\Dto\InfoParam;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class LotusService implements OrderDetailInterface
{
    public function __construct(
        protected LotusApi $api,
        protected EntityManagerInterface $em,
        private array $supportedSources
    ) {
    }

    public function supports(string $source): bool
    {
        return in_array($source, $this->supportedSources);
    }

    #[ArrayShape(['title' => 'null|string', 'data' => 'array|null'])]
    public function getDetail(Order $order): ?array
    {
        $param = new InfoParam($order->getId()->toRfc4122());

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
}
