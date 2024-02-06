<?php

namespace App\Repository;

use App\Entity\PaymentHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentHistory[]    findAll()
 * @method PaymentHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentHistory::class);
    }
}
