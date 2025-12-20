<?php

namespace App\Repository;

use App\Entity\Me;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Me>
 */
class MeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Me::class);
    }

    public function findUnique(): ?Me
    {
        $result = $this->createQueryBuilder('m')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        return $result[0] ??= null;
    }
}
