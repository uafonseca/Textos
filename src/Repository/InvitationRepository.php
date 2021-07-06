<?php

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    /**
     * @return Invitation[] Returns an array of Invitation objects
     */
    public function findByUser(User $user, UserGroup $group)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.user = :user')
            ->andWhere('i.userGroup = :group')
            ->setParameter('user', $user)
            ->setParameter('group', $group)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
   

    /*
    public function findOneBySomeField($value): ?Invitation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
