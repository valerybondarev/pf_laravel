<?php

namespace App\Repository;

use App\Dto\Controller\OrdersFilterDto;
use App\Dto\Controller\ProductDto;
use App\Dto\PagingResult;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    private function getFilteredQuery(OrdersFilterDto $params): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if (!empty($params->product)) {
            $queryBuilder
                ->innerJoin('o.paymentBasis', 'b')
                ->andWhere('b.product in (:product)')
                ->setParameter('product', $params->product);
        }
        if (!empty($params->status)) {
            $queryBuilder
                ->andWhere('o.status in (:status)')
                ->setParameter('status', $params->status);
        }
        if (!empty($params->clientId)) {
            $queryBuilder
                ->andWhere('o.clientId = :clientId')
                ->setParameter('clientId', $params->clientId);
        }
        if (!empty($params->agentId)) {
            $queryBuilder
                ->andWhere('o.agentId = :agentId')
                ->setParameter('agentId', $params->agentId);
        }

        return $queryBuilder;
    }

    public function getWithPaging(OrdersFilterDto $params): PagingResult
    {
        $total = $this->getFilteredQuery($params)
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($total) {
            $data = $this->getFilteredQuery($params)
                ->orderBy('o.' . $params->getSort(), $params->sortBy)
                ->setMaxResults($params->perPage)
                ->setFirstResult($params->perPage * ($params->page - 1))
                ->getQuery()
                ->getResult();
        } else {
            $data = [];
        }

        return new PagingResult($data, $total, $params->page, $params->perPage);
    }

    public function getByProduct(ProductDto $dto): ?Order
    {
        $unitIds[] = $dto->unitId;
        if (!empty($dto->estimationId)) {
            $unitIds[] = $dto->estimationId;
        }

        $qb = $this->createQueryBuilder('o');
        $qb
            ->innerJoin('o.paymentBasis', 'b')
            ->andWhere('b.product = :product')->setParameter('product', $dto->product)
            ->andWhere('b.unitId in (:unitIds)')->setParameter('unitIds', $unitIds)
            ->andWhere('o.deletedAt is null')
            ->orderBy('o.createdAt', 'desc')
            ->setMaxResults(1);
        if (!is_null($dto->paymentNumber)) {
            $qb->andWhere('b.paymentNumber = :paymentNumber')->setParameter('paymentNumber', $dto->paymentNumber);
        }
        if (!is_null($dto->payNumber)) {
            $qb->andWhere('b.payNumber = :payNumber')->setParameter('payNumber', $dto->payNumber);
        }

        $result = $qb->getQuery()->getResult();

        return count($result) ? current($result) : null;
    }
}
