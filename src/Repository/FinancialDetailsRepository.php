<?php

namespace App\Repository;

use App\Entity\FinancialDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FinancialDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinancialDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinancialDetails[]    findAll()
 * @method FinancialDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinancialDetails::class);
    }

    // /**
    //  * @return FinancialDetails[] Returns an array of FinancialDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FinancialDetails
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
