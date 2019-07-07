<?php

namespace App\Repository;

use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    // /**
    //  * @return Invitation[] Returns an array of Invitation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findPossibleInvitations($iid, $uid, $ownerRequired): ?Invitation
    {
        try {
            if($ownerRequired) {
                return $this->createQueryBuilder('i')
                    ->andWhere('i.id = :id AND i.fromId = :user')
                    ->setParameter('id', $iid)
                    ->setParameter('user', $uid)
                    ->getQuery()
                    ->getOneOrNullResult();
            } else {
                return $this->createQueryBuilder('i')
                    ->andWhere('i.id = :id AND i.toId = :user')
                    ->setParameter('id', $iid)
                    ->setParameter('user', $uid)
                    ->getQuery()
                    ->getOneOrNullResult();
            }
        } catch (NonUniqueResultException $e) {
            return null;
        }
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
