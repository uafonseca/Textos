<?php

namespace App\Repository;

use App\Entity\SingleQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SingleQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SingleQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SingleQuestion[]    findAll()
 * @method SingleQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingleQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SingleQuestion::class);
    }

    // /**
    //  * @return SingleQuestion[] Returns an array of SingleQuestion objects
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
    public function findOneBySomeField($value): ?SingleQuestion
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
