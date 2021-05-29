<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\CourseVsit;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseVsit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseVsit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseVsit[]    findAll()
 * @method CourseVsit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseVsitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseVsit::class);
    }


    public function getLastVisit(User $user, $book){
        return $this->createQueryBuilder('c')
            ->join('c.course','book')
            ->andWhere('c.user = :user')
            ->andWhere('book = :book')
            ->setParameter('user', $user)
            ->setParameter('book', $book)
            ->orderBy('c.moment', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return CourseVsit[] Returns an array of CourseVsit objects
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
    public function findOneBySomeField($value): ?CourseVsit
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
