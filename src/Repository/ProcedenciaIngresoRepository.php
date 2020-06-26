<?php

namespace App\Repository;

use App\Entity\ProcedenciaIngreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProcedenciaIngreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcedenciaIngreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcedenciaIngreso[]    findAll()
 * @method ProcedenciaIngreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcedenciaIngresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcedenciaIngreso::class);
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
