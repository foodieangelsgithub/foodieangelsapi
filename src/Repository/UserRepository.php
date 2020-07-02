<?php

namespace App\Repository;

use App\Entity\User;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    use repositoryTrait;

    public $passwordEncoder;
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder=$passwordEncoder;
    }


    /**
     * @param array $values
     * @return mixed
     */
    public function findyByOr(array $values){
        $em = $this->getEntityManager();
        $qb= $em->createQueryBuilder();
        $qb->select('u') // string 'u' is converted to array internally
        ->from('App\Entity\User', 'u');
        
        $x=0;
        foreach ($values as $key=>$v){
            $qb->orWhere("u.{$key}= :v{$x}");
            $qb->setParameter("v{$x}", $v);
            $x++;
        }

        return $qb->getQuery()->execute();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function saveUser(User $user){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->flush($user);
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
     * @param User $user
     * @return bool
     */
    public function insertUser(User $user){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($user);
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


    /**
     * @return ArrayCollection|User[]
     */
    public function findUserByRol($rol){
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM App\Entity\User u WHERE u.roles LIKE :role'
            )->setParameter('role', "%{$rol}%");

        $users = $query->getResult();
        return $users;
    }
}
