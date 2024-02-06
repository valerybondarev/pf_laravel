<?php

namespace App\Repository;

use App\Entity\PaymentBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentBase[]    findAll()
 * @method PaymentBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentBaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentBase::class);
    }
}
