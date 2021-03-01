<?php

namespace App\Repository;

use App\Entity\HtmlCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HtmlCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method HtmlCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method HtmlCode[]    findAll()
 * @method HtmlCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HtmlCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HtmlCode::class);
    }

    // /**
    //  * @return HtmlCode[] Returns an array of HtmlCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HtmlCode
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
