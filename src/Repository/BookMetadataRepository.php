<?php

namespace App\Repository;

use App\Entity\BookMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookMetadata[]    findAll()
 * @method BookMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookMetadata::class);
    }

    // /**
    //  * @return BookMetadata[] Returns an array of BookMetadata objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookMetadata
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
