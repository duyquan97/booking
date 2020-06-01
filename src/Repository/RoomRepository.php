<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

     /**
      * @return Room[] Returns an array of Room objects
      */

    public function findBySearch($keyWord, $listId)
    {
        $data = $this->createQueryBuilder('r');
        if ($keyWord != '') {
            $data = $data->andWhere('r.name like :key OR r.province like :key OR r.district like :key OR r.street like :key')
                        ->setParameter('key', '%'.$keyWord.'%');
        }
        if ($listId != null) {
            $data = $data->andWhere('r.id IN (:listId)')
                    ->setParameter('listId', $listId);
        }


        $data = $data
            ->getQuery()
            ->getResult();

        return $data;
    }


    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
