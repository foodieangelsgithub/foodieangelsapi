<?php

namespace App\Repository;

use App\Entity\Servicio;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Servicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servicio[]    findAll()
 * @method Servicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicioRepository extends ServiceEntityRepository
{

    use repositoryTrait;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Servicio::class);
    }


    /**
     * @param Servicio $servicio
     * @return bool
     */
    public function insertServicio(Servicio $servicio){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($servicio);
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

    public function findOneByEstadoBeneficiario($id, $beneficiario){
        $q= $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.estado < :id')
            ->andWhere('u.beneficiario = :beneficiario')
            ->setParameter('id',$id)
            ->setParameter('beneficiario',$beneficiario);
        $qr=$q->getQuery();

        return $qr->getResult();
    }

    public function findOneByEstadoVoluntario($id, $voluntario){
        $q= $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.estado < :id')
            ->andWhere('u.voluntario_id = :voluntario')
            ->setParameter('id',$id)
            ->setParameter('voluntario',$voluntario);
        $qr=$q->getQuery();

        return $qr->getResult();
    }


    public function remove($entity){
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
