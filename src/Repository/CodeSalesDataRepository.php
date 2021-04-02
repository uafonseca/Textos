<?php

namespace App\Repository;

use App\Entity\CodeSalesData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeSalesData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeSalesData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeSalesData[]    findAll()
 * @method CodeSalesData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeSalesDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeSalesData::class);
    }

    // /**
    //  * @return CodeSalesData[] Returns an array of CodeSalesData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodeSalesData
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
