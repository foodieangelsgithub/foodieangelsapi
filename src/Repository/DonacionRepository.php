<?php

namespace App\Repository;

use App\Entity\Donacion;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Donacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donacion[]    findAll()
 * @method Donacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonacionRepository extends ServiceEntityRepository
{
    use repositoryTrait;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donacion::class);
    }

    // /**
    //  * @return Donacion[] Returns an array of Donacion objects
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
    public function findOneBySomeField($value): ?Donacion
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    /**
     * @param Donacion $donacion
     * @return bool
     */
    public function saveDonacion(Donacion $donacion){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();
        $donacion->setFechaModi(new \DateTime());

        try {
            $this->getEntityManagerTransaction()->persist($donacion);
            $this->getEntityManagerTransaction()->flush();
        } catch (DriverException $e) {
            $this->getEntityManagerTransaction()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        } catch (ORMException $e) {
            $this->getEntityManagerTransaction()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        }
        return true;
    }


    public function forAdminClosed(){
        $q = $this->createQueryBuilder('p')
            ->where('p.estado = 5')
            ->orderBy('p.fecha','desc')
            ->getQuery();

        return $q->getResult();
    }

    public function forAdminCancel(){
        $q = $this->createQueryBuilder('p')
            ->where('p.estado = 6 ')
            ->orderBy('p.fecha','desc')
            ->getQuery();

        return $q->getResult();
    }
    public function forAdminOpen(){
        $q = $this->createQueryBuilder('p')
            ->where('p.estado < 5 ')
            ->orderBy('p.fecha','desc')
            ->getQuery();

        return $q->getResult();
    }

    public function greatherThan($cant){
        $q = $this->createQueryBuilder('p')
            ->innerJoin('App\Entity\Servicio', 's')
            ->where('p.total > :totalFind')
            ->andwhere('p.estado <> 6')
            ->andWhere('p.id=s.donacion')
            ->setParameter('totalFind', $cant)
            ->orderBy('p.fecha','desc')
            ->getQuery();

        return $q->getResult();
    }
}
