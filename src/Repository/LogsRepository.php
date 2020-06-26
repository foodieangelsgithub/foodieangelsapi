<?php

namespace App\Repository;


use App\Entity\Logs;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Logs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logs[]    findAll()
 * @method Logs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogsRepository extends ServiceEntityRepository
{


    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logs::class);
    }


    /**
     * @param Logs $logs
     * @return bool
     */
    public function insertLogs(Logs $logs){
        if (!$this->getEntityManager()->isOpen()) {
            $this->getEntityManager()->create(
                $this->getEntityManager()->getConnection(),
                $this->getEntityManager()->getConfiguration()
            );
        }

        try {
            $this->getEntityManager()->persist($logs);
            $this->getEntityManager()->flush();
        } catch (DriverException $e) {
            $this->getEntityManager()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            $this->setMessage($output_array[0]);
            return false;
        } catch (ORMException $e) {
            $this->getEntityManager()->rollback();
            preg_match('/SQLSTATE.*/', $e->getMessage(), $output_array);
            var_dump($e);exit;
            $this->setMessage($output_array[0]);
            return false;
        }
        return true;
    }



    public function deleteData($data){
       /* try {
            $this->getConnection()->delete($this->getTable(), array('padreid' => $data['id']));
            $this->getConnection()->delete($this->getTable(), array('id' => $data['id']));
        }catch (DriverException $er){
            return false;
        }
       */
    }
}