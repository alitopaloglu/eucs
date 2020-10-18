<?php

namespace App\Repository;

use App\Entity\Provider2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Provider2|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provider2|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provider2[]    findAll()
 * @method Provider2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Provider2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider2::class);
    }

    // /**
    //  * @return Provider2[] Returns an array of Provider2 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Provider2
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
