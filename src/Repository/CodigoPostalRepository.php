<?php

namespace App\Repository;

use App\Entity\CodigoPostal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodigoPostal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodigoPostal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodigoPostal[]    findAll()
 * @method CodigoPostal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodigoPostalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodigoPostal::class);
    }

    // /**
    //  * @return CodigoPostal[] Returns an array of CodigoPostal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodigoPostal
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Búsqueda en Kilómetros
     * @param $lat
     * @param $lon
     * @param $distance
     * @return mixed
     */
    public function getQueryLatitu($lat, $lon, $distance=2){

        $query=$this->_em->createQuery(/** @lang mysql */
            "SELECT a, 
	 (6371 * acos( cos( radians($lat) ) * cos( radians( a.lat ) ) * cos( radians( $lon ) - radians(a.lon) ) + sin( radians( $lat) ) * sin( radians(a.lat) ) )) AS distance 
	 FROM App\Entity\CodigoPostal a WHERE a.lat<>'' AND a.lon<>''
	  having distance < {$distance}
	  ORDER BY a.codigo desc");

        $query2=$this->_em->createQueryBuilder("codigopostal")->select(" C, 
            (
                (
                    (
                        acos(
                            sin(( $lat * pi() / 180))
                            *
                            sin(( `lat` * pi() / 180)) + cos(( $lat * pi() /180 ))
                            *
                            cos(( `lat` * pi() / 180)) * cos((( $lon - `lon`) * pi()/180)))
                    ) * 180/pi()
                ) * 60 * 1.1515 * 1.609344
            )
        as distance FROM `codigopostal`")->having("distance <= {$distance}");


        return  $query->getResult();
        //return $queryQ->getResult();
    }
}
