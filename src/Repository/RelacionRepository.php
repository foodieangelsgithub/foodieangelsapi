<?php

namespace App\Repository;

use App\Entity\Relacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Relacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relacion[]    findAll()
 * @method Relacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relacion::class);
    }

    // /**
    //  * @return Relacion[] Returns an array of Relacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Relacion
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
