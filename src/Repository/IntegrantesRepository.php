<?php

namespace App\Repository;

use App\Entity\Integrantes;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Integrantes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Integrantes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Integrantes[]    findAll()
 * @method Integrantes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntegrantesRepository extends ServiceEntityRepository
{

    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Integrantes::class);
    }

    // /**
    //  * @return Integrantes[] Returns an array of Integrantes objects
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
    public function findOneBySomeField($value): ?Integrantes
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function deleteIntegranteByBeneficiario($id){
        $qb = $this->_em->createQueryBuilder()
            ->delete('App\Entity\Integrantes','i')
            ->where('i.beneficiario = :val')
            ->setParameter('val', $id);
        try{
            $qb->getQuery()->execute();
        }catch (\Exception $e){
            return false;
        }
        return true;

    }

    public function deleteIntegranteById($id){
        $qb = $this->_em->createQueryBuilder()
            ->delete('App\Entity\Integrantes', 'i')
            ->where('i.id = :val')
            ->setParameter('val', $id);
        try{
            $qb->getQuery()->execute();
        }catch (\Exception $e){
            return false;
        }
        return true;

    }

    /**
     * @param Integrantes $integrantes
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveIntegrante(Integrantes $integrantes){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($integrantes);
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
