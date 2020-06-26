<?php

namespace App\Controller;

use App\Entity\Beneficiario;
use App\Entity\CodigoPostal;
use App\Entity\Integrantes;
use App\Entity\User;
use App\Repository\BeneficiarioRepository;
use App\Repository\CodigoPostalRepository;
use App\Repository\IngresosRepository;
use App\Repository\IntegrantesRepository;
use App\Repository\ProcIngresosRepository;
use App\Repository\RelacionRepository;
use App\Repository\SituacionLaboralRepository;
use App\Repository\UserRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProvinciaRepository;
use App\Service\PushNotification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BeneficiarioController extends BaseController
{

    private $beneficiarioRepository;
    private $integrantesRepository;

    public $codigoPostalRepository;
    public $procIngresosRepository;
    public $ingresosRepository;
    public $situacionLaboralRepository;
    public $relacionRepository;



    public function __construct(BeneficiarioRepository $beneficiarioRepository, UserRepository $userRepository,
            ProvinciaRepository $provinciaRepository, MunicipioRepository $municipioRepository,
            IntegrantesRepository $integrantesRepository, CodigoPostalRepository $codigoPostalRepository,
            ProcIngresosRepository $procIngresosRepository, IngresosRepository $ingresosRepository,
            SituacionLaboralRepository $situacionLaboralRepository, RelacionRepository $relacionRepository)
    {
        parent::__construct();
        $this->beneficiarioRepository   = $beneficiarioRepository;
        $this->userRepository           = $userRepository;
        $this->provinciaRepository      = $provinciaRepository;
        $this->municipioRepository      = $municipioRepository;
        $this->integrantesRepository    = $integrantesRepository;

        $this->codigoPostalRepository   = $codigoPostalRepository;
        $this->procIngresosRepository   = $procIngresosRepository;
        $this->ingresosRepository       = $ingresosRepository;
        $this->situacionLaboralRepository = $situacionLaboralRepository;
        $this->relacionRepository       = $relacionRepository;

    }



    /**
     * @Route("/beneficiario", name="beneficiario")
     * @Route("/beneficiario/{id}", name="beneficiarioid")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction(Request $request){

        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->setrequest($request);
        if($this->getrequest()->getMethod()=='GET'){
            return $this->getAll()->returnResponse();
        }else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            return $this->insertUpdate()->returnResponse();
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function getAll()
    {
        $data=array();
        $message='';
        if($this->getrequest() && $this->getrequest()->get('id')){
            if($beneficiario=$this->beneficiarioRepository->findOneBy(array('userid'=>$this->getrequest()->get('id')))){
                $data=$beneficiario->objectToArray();
            }else
                $data=$this->getNoData();
        }elseif($beneficiarios=$this->beneficiarioRepository->findAll()) {
            foreach ($beneficiarios as $beneficiario){
                $data[]=$beneficiario->objectToArray();
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
        $data=array();
        $message='';
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
                        $beneficiario = $this->beneficiarioRepository->findOneBy(array('userid' => $user->getId()));
                        if(!$beneficiario){
                            $this->jsonError('Beneficiario no existe');
                        }else{
                            $beneficiario=$this->fillBeneficiario($beneficiario);
                            if($beneficiario) {
                                $beneficiario->setUserid($user);
                                if ($this->beneficiarioRepository->saveBeneficiario($beneficiario)) {
                                    $integrantes=false;
                                    $integrantesDelete = $this->integrantesRepository->deleteIntegranteByBeneficiario($beneficiario->getId());
                                    if ($integrantesDelete){
                                        $integrantesInsert=true;
                                        foreach($this->getrequest()->get('integrantes') as $i){
                                            $integrantes=$this->fillIntegrantes(new Integrantes(), $i);
                                            $integrantes->setBeneficiario($beneficiario);
                                            if(!$this->integrantesRepository->saveIntegrante($integrantes)){
                                                $this->integrantesRepository->deleteIntegranteByBeneficiario($beneficiario->getId());
                                                $integrantesInsert=false;
                                                break;
                                            }else{
                                                $this->integrantesRepository->getEntityManagerTransaction()->commit();
                                            }
                                        }

                                    }

                                    if($integrantesDelete && $integrantesInsert){
                                        $this->beneficiarioRepository->getEntityManagerTransaction()->commit();
                                        $this->userRepository->getEntityManagerTransaction()->commit();
                                        $integrantes=$this->integrantesRepository->findBy(array('beneficiario'=>$beneficiario->getId()));
                                        $beneficiario=$this->beneficiarioRepository->findOneBy(array('id'=>$beneficiario->getId()));
                                        $beneficiario->setIntegrantes($integrantes);
                                        $data=$beneficiario->objectToArray();
                                        $this->jsonSuccess($data, $message);
                                    }else {
                                        $this->beneficiarioRepository->getEntityManagerTransaction()->rollback();
                                        $this->userRepository->getEntityManagerTransaction()->rollback();
                                    }
                                } else {
                                    $this->userRepository->getEntityManagerTransaction()->rollback();
                                    $this->jsonError($this->beneficiarioRepository->getMessage());
                                }
                            }else{
                                $this->userRepository->getEntityManagerTransaction()->rollback();
                                $this->jsonError($this->beneficiarioRepository->getMessage());
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
            }elseif(!$this->notEmptyOnrequest('email') && !$this->notEmptyOnrequest('telefono')){
                $this->jsonError('Email y/o teléfono están vacíos');
            }elseif($this->userRepository->findOneBy(array('username'=>$this->getrequest()->get('nombreUsuario')))){
                $this->jsonError('Este nombre de usuario existe');
            }else{
                if($this->notEmptyOnrequest(['nombreUsuario','contrasenia'])){
                    $this->getrequest()->attributes->set('roles', ['ROLE_BENEFICIARIO']);
                    $this->getrequest()->attributes->set('usernameIdUnique', $this->getrequest()->get('nombreUsuario'));
                    $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    $user = $this->fillUser(new User());
                    $user->setDatecreate(new \DateTime());
                    if($this->userRepository->insertUser($user)){
                        if($this->findInrequest(['nombre','apellidos','telefono','sitLaboral','discapacidad','fnacim','ingresos','codPostal','procIngresos','lopd','direccion','provincia','municipio'])){
                            $beneficiario = $this->fillBeneficiario(new Beneficiario());
                            if($beneficiario){
                                $beneficiario->setUserid($user)->setFecha(new \DateTime());
                                if($this->beneficiarioRepository->insertBeneficiario($beneficiario)){
                                    $integrantes=false;
                                    $integrantesDelete = $this->integrantesRepository->deleteIntegranteByBeneficiario($beneficiario->getId());
                                    if ($integrantesDelete){
                                        $integrantesInsert=true;
                                        foreach($this->getrequest()->get('integrantes') as $i){
                                            if(is_array($i) && count($i)>0){
                                                $integrantes=$this->fillIntegrantes(new Integrantes(), $i);
                                                if($integrantes){
                                                    $integrantes->setBeneficiario($beneficiario);
                                                    if(!$this->integrantesRepository->saveIntegrante($integrantes)){
                                                        $this->integrantesRepository->deleteIntegranteByBeneficiario($beneficiario->getId());
                                                        $integrantesInsert=false;
                                                        break;
                                                    }else{
                                                        $this->integrantesRepository->getEntityManagerTransaction()->commit();
                                                    }
                                                }else{
                                                    $integrantesDelete=null;
                                                }
                                            }

                                        }

                                    }

                                    if($integrantesDelete && $integrantesInsert){
                                        $this->beneficiarioRepository->getEntityManagerTransaction()->commit();
                                        $this->userRepository->getEntityManagerTransaction()->commit();
                                        $integrantes=$this->integrantesRepository->findBy(array('beneficiario'=>$beneficiario->getId()));
                                        $beneficiario=$this->beneficiarioRepository->findOneBy(array('id'=>$beneficiario->getId()));
                                        $beneficiario->setIntegrantes($integrantes);
                                        $data=$beneficiario->objectToArray();
                                        $this->jsonSuccess($data);
                                    }else {
                                        $this->beneficiarioRepository->getEntityManagerTransaction()->rollback();
                                        $this->userRepository->getEntityManagerTransaction()->rollback();
                                        $this->jsonError($this->integrantesRepository->getMessage());
                                    }
                                } else {
                                    $this->userRepository->getEntityManagerTransaction()->rollback();
                                    $this->jsonError($this->beneficiarioRepository->getMessage());
                                }
                            }else{
                                $this->jsonError($this->beneficiarioRepository->getMessage());
                                $this->userRepository->getEntityManagerTransaction()->rollback();
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


    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function fillUser(User $user)
    {
        return parent::fillUser($user); // TODO: Change the autogenerated stub
    }

    /**
     * @param Beneficiario $beneficiario
     * @return Beneficiario|boolean
     * @throws \Exception
     */
    private function fillBeneficiario(Beneficiario $beneficiario){

        $beneficiario->setNombre($this->findInrequest('nombre')?$this->getrequest()->get('nombre'):$beneficiario->getNombre())
        ->setApellidos($this->findInrequest('apellidos')?$this->getrequest()->get('apellidos'):$beneficiario->getApellidos())
        ->setTelefono($this->findInrequest('telefono')?$this->getrequest()->get('telefono'):$beneficiario->getTelefono());
        $beneficiario->setDiscapacidad($this->findInrequest('discapacidad')?$this->getrequest()->get('discapacidad'):$beneficiario->getDiscapacidad())
        ->setFnacim($this->findInrequest('fnacim')?new \DateTime($this->getrequest()->get('fnacim')):$beneficiario->getFnacim())
        ->setCodPostal($this->getrequest()->get('codPostal')?$this->getrequest()->get('codPostal'):$beneficiario->getCodPostal())
        ->setLopd(($this->findInrequest('lopd')&& $this->notEmptyOnrequest('lopd'))?filter_var(    $this->getrequest()->get('lopd'), FILTER_VALIDATE_BOOLEAN):$beneficiario->getLopd());
        $beneficiario->setDireccion($this->findInrequest('direccion')?$this->getrequest()->get('direccion'):$beneficiario->getDireccion());


        $sitLaboral = $this->situacionLaboralRepository->findOneBy(['id'=>$this->getrequest()->get('sitLaboral')]);
        if($sitLaboral){
            $beneficiario->setSitLaboral($sitLaboral);
        }else{
            $this->beneficiarioRepository->setMessage('El valor situación laboral no existe');
            return false;
        }


        $ingresos = $this->ingresosRepository->findOneBy(['id'=>$this->getrequest()->get('ingresos')]);
        if($ingresos){
            $beneficiario->setIngresos($ingresos);
        }else{
            $this->beneficiarioRepository->setMessage('El valor ingresos no existe');
            return false;
        }

        $procIngresos = $this->procIngresosRepository->findOneBy(['id'=>$this->getrequest()->get('procIngresos')]);
        if($procIngresos){
            $beneficiario->setProcIngresos($procIngresos);
        }else{
            $this->beneficiarioRepository->setMessage('La procedencia de ingresos no existe');
            return false;
        }

        $provincia = $this->provinciaRepository->findOneBy(['id'=>$this->getrequest()->get('provincia')]);
        if($provincia){
            $beneficiario->setProvincia($provincia);
        }

        $municipio = $this->municipioRepository->findOneBy(['id'=>$this->getrequest()->get('municipio')]);
        if($municipio){
            $beneficiario->setMunicipio($municipio);
        }
        return $beneficiario;

    }

    /**
     * @param Integrantes $integrantes
     * @param $integrante
     * @return Integrantes|bool
     */
    private function fillIntegrantes(Integrantes $integrantes, $integrante){


        $sitLaboral = $this->situacionLaboralRepository->findOneBy(['id'=>$integrante['sitLaboral']]);

        if($sitLaboral){
            $integrantes->setSitLaboral($sitLaboral);
        }else{
            $this->integrantesRepository->setMessage('El valor situación laboral no existe');
            return false;
        }

        $relacion = $this->relacionRepository->findOneBy(['id'=>$integrante['relacion']]);
        if($relacion){
            $integrantes->setRelacion($relacion);
        }else{
            $this->integrantesRepository->setMessage('El valor situación laboral no existe');
            return false;
        }

        if(!array_key_exists('fnacimAnio',$integrante) || !array_key_exists('fnacimMes',$integrante) || !array_key_exists('fnacimDia',$integrante)){
            $this->integrantesRepository->setMessage('Fecha de nacimiento está incorrecta');
            return false;
        }

        $integrantes->setNombre($integrante['nombre']?$integrante['nombre']:$integrantes->getNombre())
        ->setApellidos($integrante['apellidos']?$integrante['apellidos']:$integrantes->getApellidos())
        ->setTelefono($integrante['telefono']?$integrante['telefono']:$integrantes->getTelefono())
        //->setSitLaboral($integrante['sitLaboral']?$integrante['sitLaboral']:$integrantes->getSitLaboral())
        ->setDiscapacidad(isset($integrante['discapacidad'])&&is_numeric($integrante['discapacidad'])?$integrante['discapacidad']:$integrantes->getDiscapacidad()+0)
        //->setRelacion($integrante['relacion']?$integrante['relacion']:$integrantes->getRelacion())
        ->setFnacim($integrante['fnacimAnio']?new \DateTime("{$integrante['fnacimAnio']}/{$integrante['fnacimMes']}/{$integrante['fnacimDia']}"):$integrantes->getFnacim());

        return $integrantes;

    }



    public function index(){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('beneficiario/index.html.twig', [
            'controller_name' => 'BeneficiarioController',
        ]);
    }



}