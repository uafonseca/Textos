<?php

namespace App\Repository;

use App\Entity\Mail;
use App\Entity\MailResponse;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailResponse[]    findAll()
 * @method MailResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailResponse::class);
    }

    /**
     * Undocumented function
     *
     * @param Mail $mail
     * @param User $user
     * @return void
     */
    public function findByMailAndUser(Mail $mail, User $user)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.mail = :mail')
            ->andWhere('m.User = :user')
            ->setParameter('mail', $mail)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return MailResponse[] Returns an array of MailResponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MailResponse
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
