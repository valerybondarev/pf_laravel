<?php

namespace App\Repository;

use App\Entity\Visited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visited|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visited|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visited[]    findAll()
 * @method Visited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visited::class);
    }
}
