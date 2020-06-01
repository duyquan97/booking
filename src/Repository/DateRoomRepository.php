<?php

namespace App\Repository;

use App\Entity\DateRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateRoom[]    findAll()
 * @method DateRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateRoom::class);
    }

     /**
      * @return DateRoom[] Returns an array of DateRoom objects
      */

    public function findOrDerByFromDate($now)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.fromDate >= :now')
            ->setParameter('now',$now)
            ->orderBy('d.fromDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?DateRoom
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
