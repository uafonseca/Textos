<?php

namespace App\Repository;

use App\Entity\GlobalLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GlobalLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalLink[]    findAll()
 * @method GlobalLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalLink::class);
    }

    // /**
    //  * @return GlobalLink[] Returns an array of GlobalLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GlobalLink
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
