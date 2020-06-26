<?php

namespace App\Controller;

use App\Entity\Horarios;
use App\Entity\Proveedor;
use App\Entity\User;
use App\Repository\HorariosRepository;
use App\Repository\ProveedorRepository;
use App\Repository\IntegrantesRepository;
use App\Repository\UserRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProvinciaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProveedorController extends BaseController
{

    private $proveedorRepository;
    private $integrantesRepository;
    public $horariosRepository;



    public function __construct(ProveedorRepository $proveedorRepository,
                                UserRepository $userRepository, ProvinciaRepository $provinciaRepository,
                                MunicipioRepository $municipioRepository, IntegrantesRepository $integrantesRepository, HorariosRepository $horariosRepository)
    {
        parent::__construct();
        $this->proveedorRepository      = $proveedorRepository;
        $this->userRepository           = $userRepository;
        $this->provinciaRepository      = $provinciaRepository;
        $this->municipioRepository      = $municipioRepository;
        $this->integrantesRepository    = $integrantesRepository;
        $this->horariosRepository       = $horariosRepository;
    }

    /**
     * @Route("/proveedor", name="proveedor")
     * @Route("/proveedor/{id}", name="proveedorid")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->setrequest($request);
        if ($this->getrequest()->getMethod() == 'GET') {
            return $this->getAll()->returnResponse();
        } else {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            return $this->insertUpdate()->returnResponse();
        }
    }

    /**
     * @return $this
     */
    private function getAll()
    {
        $data=array();
        $message='';
        if($this->getrequest() && $this->getrequest()->get('id')){
            if($proveedor=$this->proveedorRepository->findOneBy(array('userid'=>$this->getrequest()->get('id')))){
                $data=$proveedor->objectToArray();
            }else
                $message=$this->getNoData();
        }elseif($proveedors=$this->proveedorRepository->findAll()) {
            foreach ($proveedors as $proveedor){
                $data[]=$proveedor->objectToArray();
            }
        }else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data, $message);
        return $this;
    }


    /**
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function insertUpdate(){
        if($this->notEmptyOnrequest('id') && $this->getrequest()->get('id')>0){

            $user=$this->userRepository->findOneBy(array('id'=>$this->getrequest()->get('id')));
            if($user){
                $userFindEmail=$userFindTelefono=null;
                if(($this->notEmptyOnrequest('email') && $user->getEmail()!=$this->getrequest()->get('email'))){
                    $userFindEmail=$this->userRepository->findOneBy(array('email'=>$this->getrequest()->get('email')));
                }
                if($this->notEmptyOnrequest('telefono') && $user->getTelephone()!=$this->getrequest()->get('telefono')){
                    $userFindTelefono=$this->userRepository->findOneBy(array('telephone'=>$this->getrequest()->get('telefono')));
                }

                if($userFindEmail || $userFindTelefono){
                    $this->jsonError('Ese email/teléfono ya pertenece a otro usuario');
                }else{
                    if($this->notEmptyOnrequest(['contrasenia'])){
                        $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    }
                    $this->fillUser($user);
                    if(!$this->userRepository->saveUser($user)){
                        $this->jsonError($this->userRepository->getMessage());
                    }else {
                        $proveedor = $this->proveedorRepository->findOneBy(array('userid' => $user->getId()));
                        if(!$proveedor){
                            $this->jsonError('Proveedor no existe');
                        }else{
                            $proveedor=$this->fillProveedor($proveedor);
                            $proveedor->setUserid($user);
                            if ($this->proveedorRepository->saveProveedor($proveedor)) {

                                $this->horariosRepository->deleteHorariosByProveedor($proveedor->getId());
                                $this->fillHorarios($proveedor);
                                $this->guardar($proveedor);

                            }else {
                                $this->proveedorRepository->getEntityManagerTransaction()->rollback();
                                $this->userRepository->getEntityManagerTransaction()->rollback();
                                $this->jsonError($this->proveedorRepository->getMessage());
                            }
                        }
                    }
                }
            }else{
                $this->jsonError('Este usuario no existe no se puede actualizar');
            }
        }else{
            $user=$userFindEmail=$userFindTelefono=null;
            if($this->notEmptyOnrequest('email') && $this->notEmptyOnrequest('telefono')){
                $user = $this->userRepository->findyByOr(array('email'=>$this->getrequest()->get('email'), 'telephone'=>$this->getrequest()->get('telefono')));

            }else{
                if($this->notEmptyOnrequest('email')){
                    $userFindEmail=$this->userRepository->findOneBy(array('email'=>$this->getrequest()->get('email')));
                }
                if($this->notEmptyOnrequest('telefono')){
                    $userFindTelefono=$this->userRepository->findOneBy(array('telephone'=>$this->getrequest()->get('telefono')));
                }
            }

            if($user || $userFindEmail || $userFindTelefono){
                $this->jsonError('Ese email/teléfono ya pertenece a otro usuario');
            }elseif(!$this->notEmptyOnrequest('email') && !$this->notEmptyOnrequest('telefono')) {
                $this->jsonError('Email y/o teléfono están vacíos');
            }elseif($this->userRepository->findOneBy(array('username'=>$this->getrequest()->get('nombreUsuario')))){
                $this->jsonError('Este nombre de usuario existe');
            }else{
                if($this->findInrequest(['nombreUsuario','contrasenia'])){
                    $this->getrequest()->attributes->set('roles', ['ROLE_PROVEEDOR']);
                    $this->getrequest()->attributes->set('usernameIdUnique', $this->getrequest()->get('nombreUsuario'));
                    $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    $user = $this->fillUser(new User());
                    $user->setDatecreate(new \DateTime());
                    if($this->userRepository->insertUser($user)){
                        if($this->findInrequest(['nombre','apellidos','direccion','provincia','municipio','telefono','codPostal','redesSociales','lopd'])){
                            $proveedor = $this->fillProveedor(new Proveedor());
                            $proveedor->setUserid($user)->setFecha(new \DateTime());
                            if($this->proveedorRepository->insertProveedor($proveedor)) {
                                $horarioDelete = $this->horariosRepository->deleteHorariosByProveedor($proveedor->getId());
                                if ($horarioDelete) {
                                    $this->fillHorarios($proveedor);
                                }
                                $this->guardar($proveedor);
                            } else {
                                $this->proveedorRepository->getEntityManagerTransaction()->rollback();
                                $this->userRepository->getEntityManagerTransaction()->rollback();
                                $this->jsonError($this->integrantesRepository->getMessage());
                            }
                        }else{
                            $this->jsonError("Faltan campos obligatorios {$this->elementNotFound}");
                        }
                    }else{
                        $this->jsonError($this->userRepository->getMessage());
                    }

                }else{
                    $this->jsonError('El nombre de usuario y la contraseña son obligatorias');
                }
            }

        }
        return $this;
    }

    private function guardar($proveedor){
        $this->proveedorRepository->getEntityManagerTransaction()->commit();
        $this->userRepository->getEntityManagerTransaction()->commit();
        $proveedor = $this->proveedorRepository->findOneBy(array('userid'=>$proveedor->getUserid()->getId()));
        $horarios=$this->horariosRepository->findBy(['proveedorid'=>$proveedor->getId()]);
        $proveedor->setHorarios($horarios);
        $data = $proveedor->objectToArray();
        $this->jsonSuccess($data);
    }


    public function fillUser(User $user)
    {
        return parent::fillUser($user); // TODO: Change the autogenerated stub
    }

    /**
     * @param Proveedor $proveedor
     * @return Proveedor
     * @throws \Exception
     */
    private function fillProveedor(Proveedor $proveedor){

        $proveedor->setNombre($this->findInrequest('nombre')?$this->getrequest()->get('nombre'):$proveedor->getNombre());
        $proveedor->setApellidos($this->findInrequest('apellidos')?$this->getrequest()->get('apellidos'):$proveedor->getApellidos());
        $proveedor->setDireccion($this->findInrequest('direccion')?$this->getrequest()->get('direccion'):$proveedor->getDireccion());
        $proveedor->setTelefono($this->findInrequest('telefono')?$this->getrequest()->get('telefono'):$proveedor->getTelefono());
        $proveedor->setCodPostal($this->getrequest()->get('codPostal')?$this->getrequest()->get('codPostal'):$proveedor->getCodPostal());
        $proveedor->setRedesSociales($this->findInrequest('redesSociales')?filter_var(    $this->getrequest()->get('redesSociales'), FILTER_VALIDATE_BOOLEAN):$proveedor->getRedesSociales());
        $proveedor->setLopd(($this->findInrequest('lopd')&& $this->notEmptyOnrequest('lopd'))?filter_var(    $this->getrequest()->get('lopd'), FILTER_VALIDATE_BOOLEAN):$proveedor->getLopd());



        $provincia = $this->provinciaRepository->findOneBy(['id'=>$this->getrequest()->get('provincia')]);
        if($provincia){
            $proveedor->setProvincia($provincia);
        }

        $municipio = $this->municipioRepository->findOneBy(['id'=>$this->getrequest()->get('municipio')]);
        if($municipio){
            $proveedor->setMunicipio($municipio);
        }
        return $proveedor;

    }


    /**
     * @param Proveedor $proveedor
     * @return bool
     * @throws \Exception
     */
    private function fillHorarios(Proveedor $proveedor){

        foreach ($this->getrequest()->get('horario') as $horario) {
            if (is_array($horario) && count($horario) > 0) {

                if(count($horario['rangoHoras'])==0){
                    $horarios = new Horarios();
                    $horarios->setDia($horario['dia'])
                        ->setProveedorid($proveedor)
                        ->setAbierto($horario['abierto']);
                    if ($this->horariosRepository->saveHorarios($horarios)) {
                        $this->horariosRepository->getEntityManagerTransaction()->commit();
                    } else {
                        $this->horariosRepository->getEntityManagerTransaction()->rollback();
                    }
                }else{
                    foreach ($horario['rangoHoras'] as $rangoHoras) {
                        $horarios = new Horarios();
                        $horarios->setDia($horario['dia'])
                            ->setProveedorid($proveedor)
                            ->setAbierto($horario['abierto'])
                            ->setAbre($rangoHoras['abre'])
                            ->setCierra($rangoHoras['cierra']);
                        if ($this->horariosRepository->saveHorarios($horarios)) {
                            $this->horariosRepository->getEntityManagerTransaction()->commit();
                        } else {
                            $this->horariosRepository->getEntityManagerTransaction()->rollback();
                        }
                    }
                }
            }
        }
        return true;
    }



    public function index(){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('proveedor/index.html.twig', [
            'controller_name' => 'ProveedorController',
        ]);
    }

}
