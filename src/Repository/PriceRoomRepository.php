<?php

namespace App\Repository;

use App\Entity\PriceRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceRoom[]    findAll()
 * @method PriceRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceRoom::class);
    }

     /**
      * @return PriceRoom[] Returns an array of PriceRoom objects
      */

    public function findOrDerByPrice()
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.currency','ASC')
            ->addOrderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findSetRoom($price) {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price = :price')
            ->setParameter('price',$price)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?PriceRoom
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
