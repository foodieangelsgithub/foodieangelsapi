<?php

namespace App\Repository;

use App\Entity\Voluntario;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Voluntario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voluntario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voluntario[]    findAll()
 * @method Voluntario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoluntarioRepository extends ServiceEntityRepository
{
    use repositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voluntario::class);
    }

    // /**
    //  * @return Voluntario[] Returns an array of Voluntario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voluntario
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Voluntario $voluntario
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveVoluntario(Voluntario $voluntario){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();


        try {
            $this->getEntityManagerTransaction()->flush($voluntario);
        } catch (DriverException $e) {
            $this->getEntityManagerTransaction()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        }
        return true;
    }


    /**
     * @param Voluntario $voluntario
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertVoluntario(Voluntario $voluntario){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($voluntario);
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


    public function getAmbito($codigo){

        $entityManager = $this->getEntityManager();


        foreach ($codigo as $code){
            $d[]= "JSON_SEARCH(b.ambitoRecogida,'ALL', '{$code}') > 1 ";

        }
        $query = $entityManager->createQuery(
            "SELECT b
            FROM App\Entity\Voluntario b
            WHERE ".implode(' or ',$d ));


        // returns an array of Product objects
        return $query->getResult();
/*
        $qb = $this->createQueryBuilder('u');
        $qb->select('u')
            ->where('u.ambitoRecogida LIKE :ambito')
            ->setParameter('ambito', '%"'.$codigo.'"%');

        return $qb->getQuery()->getResult();
*/
    }

}
