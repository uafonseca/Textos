<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    
    /**
     * Method getBooks
     *
     * @param User $user [explicite description]
     *
     * @return User[]|null
     */
    public function getBooks(User $user)
    {
        return $this->createQueryBuilder('b')
            ->join('b.codes', 'code')
            ->join('code.user', 'user')
            ->where('user =:user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     * @return int|mixed|string
     */
    public  function getBoksByLimit(int $limit = 1){
        return $this->createQueryBuilder('book')
            ->orderBy('book.createdAt','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function getTotalBooks(){
        return $this->createQueryBuilder('book')
            ->select('count(book.id) as count')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $categories
     * @param array $stages
     * @param array $levels
     * @return int|mixed|string
     */
    public function findByFilters($categories = array(), $stages = array(), $levels = array()){
        $qb = $this->createQueryBuilder('book')
            ->join('book.category','category')
            ->where('category IN (:cat)')
            ->setParameter('cat',$categories)
            ->join('book.stage','stage')
            ->orWhere('stage IN (:stage)')
            ->setParameter('stage',$stages)
            ->join('book.level','level')
            ->orWhere('level IN (:level)')
            ->setParameter('level',$levels)
        ;
        return $qb
            ->orderBy('book.createdAt','DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
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
    public function findOneBySomeField($value): ?Book
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
