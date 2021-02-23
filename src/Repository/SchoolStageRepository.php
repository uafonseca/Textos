<?php

namespace App\Repository;

use App\Entity\SchoolStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SchoolStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolStage[]    findAll()
 * @method SchoolStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolStage::class);
    }

    // /**
    //  * @return SchoolStage[] Returns an array of SchoolStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SchoolStage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
