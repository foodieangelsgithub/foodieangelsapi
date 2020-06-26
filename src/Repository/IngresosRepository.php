<?php

namespace App\Repository;

use App\Entity\Ingresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ingresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingresos[]    findAll()
 * @method Ingresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngresosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingresos::class);
    }

    // /**
    //  * @return Ingresos[] Returns an array of Ingresos objects
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

    /*
    public function findOneBySomeField($value): ?Ingresos
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
