<?php

namespace App\Repository;

use App\Entity\ProcIngresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProcIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcIngresos[]    findAll()
 * @method ProcIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcIngresosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcIngresos::class);
    }

    // /**
    //  * @return ProcedenciaIngreso[] Returns an array of ProcedenciaIngreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProcedenciaIngreso
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
