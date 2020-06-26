<?php

namespace App\Repository;

use App\Entity\Horarios;
use App\Helper\repositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\ORM\ORMException;

/**
 * @method Horarios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Horarios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Horarios[]    findAll()
 * @method Horarios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorariosRepository extends ServiceEntityRepository
{
    use repositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Horarios::class);
    }



    /**
     * @param Horarios $horarios
     * @return bool
     */
    public function saveHorarios(Horarios $horarios){
        $this->setEntityManagerTransaction($this->getEntityManager());
        $this->getEntityManagerTransaction()->beginTransaction();

        try {
            $this->getEntityManagerTransaction()->persist($horarios);
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

    public function _saveHorarios($data){
        $horarios = new Horarios();
        $horarios->setDia($data['dia'])
            ->setAbre($data['abre'])
            ->setCierra($data['cierra'])
            ->setProveedorid($data['proveedor']);
        ;
        return $horarios;
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function insertUpdateData($data){
        if(array_key_exists('horario',$data)){
            $horarios=$data['horario'];
            unset($data['horario']);
        }
        $id=$this->insertData($data);

        if($id){
            $this->setTable('horarios');
            $this->deleteBy('proveedorid',$id);
            foreach($horarios as $horario){
                $dia=$horario['dia'];
                foreach ($horario['rangoHoras'] as $rangoHoras){
                    $abre   = $rangoHoras['abre'];
                    $cierra = $rangoHoras['cierra'];
                    $proveedor=array(
                        'proveedorid'=>$id,
                        'dia'=>$dia,
                        'abre'=>$abre,
                        'cierra'=>$cierra
                    );
                    $this->insertData($proveedor, null);
                }
            }
        }
        return $id;
    }


    public function deleteHorariosByProveedor($id){
        $qb = $this->_em->createQueryBuilder()
            ->delete('App\Entity\Horarios','i')
            ->where('i.proveedorid = :val')
            ->setParameter('val', $id);
        try{
            $qb->getQuery()->execute();
        }catch (\Exception $e){
            return false;
        }
        return true;

    }
}