<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Code;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param Book $book [explicit description]
     * @param User $user [explicit description]
     *
     * @return Code
     * @throws NonUniqueResultException
     */
    public function isBookActive(Book $book, User $user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.book = :book')
            ->andWhere('c.user = :user')
            ->setParameter('book', $book)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Book $book
     * @param string $code
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findCode(Book $book, string $code) {
        return $this->createQueryBuilder('c')
            ->andWhere('c.book = :book')
            ->andWhere('c.code = :code')
            ->setParameter('book', $book)
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param UserInterface $user
     * @return int|mixed|string
     */
    public function myCodes(UserInterface $user){
        return $this->createQueryBuilder('c')
            ->where('c.user =:user')
            ->setParameter('user',$user)
            ->getQuery()
            ->getResult();
    }

    public function buildChart(){
        return $this->createQueryBuilder('c')
            ->join('c.book', 'book')
            ->select('book.title, count(c.user) as counter')
            ->groupby('book.title')
            ->getQuery()
            ->getResult();
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
