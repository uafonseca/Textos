<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Code;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Code|null find($id, $lockMode = null, $lockVersion = null)
 * @method Code|null findOneBy(array $criteria, array $orderBy = null)
 * @method Code[]    findAll()
 * @method Code[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    /**
     * Method isBookActive
     *
     * @param Book $book [explicite description]
     * @param User $user [explicite description]
     *
     * @return Code
     */
    public function isBookActive(Book $book, User $user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.uuid = :uuid')
            ->andWhere('c.user = :user')
            ->setParameter('uuid', $book->getUuid())
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Code[] Returns an array of Code objects
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
    public function findOneBySomeField($value): ?Code
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
