<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroup[]    findAll()
 * @method UserGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }

    // /**
    //  * @return UserGroup[] Returns an array of UserGroup objects
    //  */
   
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.createdBy = :val')
            ->andWhere('u.groupName IS NOT NULL')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    
   

    /*
    public function findOneBySomeField($value): ?UserGroup
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
