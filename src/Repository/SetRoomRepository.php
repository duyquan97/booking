<?php

namespace App\Repository;

use App\Entity\DateRoom;
use App\Entity\PriceRoom;
use App\Entity\Room;
use App\Entity\SetRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SetRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method SetRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method SetRoom[]    findAll()
 * @method SetRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SetRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SetRoom::class);
    }

     /**
      * @return SetRoom[] Returns an array of SetRoom objects
      */

    public function findByExampleField($fromDate, $toDate, $fromPrice, $toPrice, $countDay) :array
    {
        $data = $this->createQueryBuilder('s')
                ->Join(Room::class, 'r', Join::WITH, 'r.id = s.room')
                ->Join(DateRoom::class, 'd', Join::WITH, ' s.dateRoom = d.id')
                ->Join(PriceRoom::class, 'p', Join::WITH, ' s.priceRoom = p.id');
        if ($fromDate != '' && $toDate != '') {
            $data = $data
                    ->andWhere('d.fromDate >= :fromDate AND d.fromDate < :toDate AND d.toDate > :fromDate AND d.toDate <= :toDate')
                    ->andWhere('s.roomCount > 0')
                    ->setParameter('fromDate', $fromDate)
                    ->setParameter('toDate', $toDate);
        }
        if ($fromPrice != '' && $toPrice != '') {
            $data = $data->andWhere('p.price >= :fromPrice AND p.price <= :toPrice')
                ->setParameter('fromPrice', $fromPrice)
                ->setParameter('toPrice', $toPrice);
        }
        if ($fromPrice != '') {
            $data = $data->andWhere('p.price >= :fromPrice')
                    ->setParameter('fromPrice', $fromPrice);
        }
        if ($toPrice != '') {
            $data = $data->andWhere('p.price <= :toPrice')
                    ->setParameter('toPrice', $toPrice);
        }
        $data = $data->select( 'r.id')
                ->groupBy('r.id');
        if ($countDay > 0) {
                $data = $data->having('COUNT(r.id) = :val')
                ->setParameter('val', $countDay);
        }
        $data = $data
            ->getQuery()
            ->getResult();
        return  $data;

    }

    public function checkRoomCount($fromDate, $toDate, $room, $person, $roomCount){
        $data = $this->createQueryBuilder('s')
            ->andWhere('s.room = :room')
            ->Join(DateRoom::class, 'd', Join::WITH, ' s.dateRoom = d.id')
            ->Join(PriceRoom::class, 'p', Join::WITH, ' s.priceRoom = p.id')
            ->andWhere('d.fromDate >= :fromDate AND d.fromDate < :toDate AND d.toDate > :fromDate AND d.toDate <= :toDate')
            ->andWhere('s.roomCount >= :roomCount')
            ->andWhere('s.person >= :person')
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->setParameter('roomCount', $roomCount)
            ->setParameter('room',$room)
            ->setParameter('person',$person)
            ->select('s.id','p.price','s.discount')
            ->getQuery()
            ->getResult();
        return $data;
    }

    public function findListBooking($fromDate, $toDate, $room){
        $data = $this->createQueryBuilder('s')
            ->andWhere('s.room = :room')
            ->Join(DateRoom::class, 'd', Join::WITH, ' s.dateRoom = d.id')
            ->andWhere('d.fromDate >= :fromDate AND d.fromDate < :toDate AND d.toDate > :fromDate AND d.toDate <= :toDate')
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->setParameter('room', $room)
            ->getQuery()
            ->getResult();
        return $data;
    }


    /*
    public function findOneBySomeField($value): ?SetRoom
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
