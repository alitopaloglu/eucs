<?php

namespace App\Repository;

use App\Entity\Provider1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Provider1|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provider1|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provider1[]    findAll()
 * @method Provider1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Provider1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider1::class);
    }

    // /**
    //  * @return Provider1[] Returns an array of Provider1 objects
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
    public function findOneBySomeField($value): ?Provider1
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
