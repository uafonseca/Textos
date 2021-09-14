<?php

namespace App\Repository;

use App\Entity\Mail;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mail[]    findAll()
 * @method Mail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mail::class);
    }

    /**
     * @return Mail[] Returns an array of Mail objects
     */
    public function findBySender(User $user)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.sender = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByRecieved(User $user){
        return $this->createQueryBuilder('m')
        ->join('m.recipients', 'user')
        ->andWhere('user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult()
    ;
    }

    

    /*
    public function findOneBySomeField($value): ?Mail
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
