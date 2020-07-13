<?php

namespace App\Repository;

use App\Entity\Proveedor;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\ORM\ORMException;


/**
 * @method Proveedor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proveedor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proveedor[]    findAll()
 * @method Proveedor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProveedorRepository extends ServiceEntityRepository
{
    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proveedor::class);
    }

    // /**
    //  * @return Proveedor[] Returns an array of Proveedor objects
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
    public function findOneBySomeField($value): ?Proveedor
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Proveedor $proveedor
     * @return bool
     *
     */
    public function saveProveedor(Proveedor $proveedor)
    {
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();
        $proveedor->setFechaModi(new \DateTime());

        try {
            $this->getEntityManagerTransaction()->flush($proveedor);
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


    /**
     * @param Proveedor $proveedor
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertProveedor(Proveedor $proveedor){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($proveedor);
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

}
