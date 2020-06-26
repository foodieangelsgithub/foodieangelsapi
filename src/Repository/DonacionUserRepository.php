<?php

namespace App\Repository;

use App\Entity\DonacionUser;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DonacionUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonacionUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonacionUser[]    findAll()
 * @method DonacionUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonacionUserRepository extends ServiceEntityRepository
{
    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonacionUser::class);
    }


    /**
     * @param DonacionUser $donacionUser
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveData(DonacionUser $donacionUser){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();


        try {
            $this->getEntityManagerTransaction()->persist($donacionUser);
            $this->getEntityManagerTransaction()->flush();
        } catch (DriverException $e) {
            $this->getEntityManagerTransaction()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        }
        return true;
    }
    // /**
    //  * @return DonacionUser[] Returns an array of DonacionUser objects
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
    public function findOneBySomeField($value): ?DonacionBeneficiario
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
