<?php

namespace App\Repository;

use App\Entity\Beneficiario;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Beneficiario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beneficiario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beneficiario[]    findAll()
 * @method Beneficiario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeneficiarioRepository extends ServiceEntityRepository
{

    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beneficiario::class);
    }


    /**
     * @param Beneficiario $beneficiario
     * @return bool
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveBeneficiario(Beneficiario $beneficiario){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        $beneficiario->setFechaModi(new \DateTime());


        try {
            $this->getEntityManagerTransaction()->flush($beneficiario);
        } catch (DriverException $e) {
            $this->getEntityManagerTransaction()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        }
        return true;
    }




    /**
     * @param Beneficiario $beneficiario
     * @return bool
     */
    public function insertBeneficiario(Beneficiario $beneficiario){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($beneficiario);
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
