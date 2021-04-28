<?php

namespace App\Repository;

use App\Entity\ChoiceAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChoiceAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChoiceAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChoiceAnswer[]    findAll()
 * @method ChoiceAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoiceAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChoiceAnswer::class);
    }

    // /**
    //  * @return ChoiceAnswer[] Returns an array of ChoiceAnswer objects
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
    public function findOneBySomeField($value): ?ChoiceAnswer
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
