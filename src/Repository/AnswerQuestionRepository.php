<?php

namespace App\Repository;

use App\Entity\AnswerQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnswerQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerQuestion[]    findAll()
 * @method AnswerQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerQuestion::class);
    }

    // /**
    //  * @return AnswerQuestion[] Returns an array of AnswerQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnswerQuestion
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
