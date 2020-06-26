<?php

namespace App\Repository;

use App\Entity\DonacionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonacionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonacionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonacionLog[]    findAll()
 * @method DonacionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonacionLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonacionLog::class);
    }

    // /**
    //  * @return DonacionLog[] Returns an array of DonacionLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DonacionLog
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
