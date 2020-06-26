<?php

namespace App\Controller;

use App\Entity\CodigoPostal;
use App\Entity\Voluntario;
use App\Entity\Integrantes;
use App\Entity\User;
use App\Repository\CodigoPostalRepository;
use App\Repository\VoluntarioRepository;
use App\Repository\IntegrantesRepository;
use App\Repository\UserRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProvinciaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VoluntarioController extends BaseController
{

    private $voluntarioRepository;
    private $integrantesRepository;

    public $codigoPostalRepository;


    public function __construct(VoluntarioRepository $voluntarioRepository, UserRepository $userRepository, ProvinciaRepository $provinciaRepository, MunicipioRepository $municipioRepository, IntegrantesRepository $integrantesRepository, CodigoPostalRepository $codigoPostalRepository)
    {
        parent::__construct();
        $this->voluntarioRepository   = $voluntarioRepository;
        $this->userRepository        = $userRepository;
        $this->provinciaRepository   = $provinciaRepository;
        $this->municipioRepository   = $municipioRepository;
        $this->integrantesRepository = $integrantesRepository;

        $this->codigoPostalRepository   =$codigoPostalRepository;
    }




    /**
     * @Route("/voluntario/codigo/{codigoPostal}", name="voluntario_codi")
     * @param $codigoPostal
     * @return JsonResponse
     */
    public function getVoluntariosCercanos($codigoPostal){
        $data=array();
        $message='';

        $codigoPostal = $this->codigoPostalRepository->findOneBy(['codigo'=>$codigoPostal]);
        $codigoPostalArray=$this->codigoPostalRepository->getQueryLatitu($codigoPostal->getLat(), $codigoPostal->getLon(), 2);

        /**
         * @var $cod CodigoPostal
         */
        if($codigoPostalArray){
            foreach ($codigoPostalArray[0] as $cod){
                if(is_object($cod)){
                    $codigos[]=$cod->getCodigo();
                }
            }
            $beneficiarios=$this->voluntarioRepository->findBy(['codPostal'=>$codigos]);
            if($beneficiarios){
                foreach ($beneficiarios as $beneficiario){
                    $data[]=$beneficiario->objectToArray();
                }
            }else{
                $message=$this->getNoData();
            }
        }else {
            $message=$this->getNoData();
        }
        return $this->jsonSuccess($data, $message)->returnResponse();

    }

    /**
     * @Route("/voluntario",  name="voluntario")
     * @Route("/voluntario/{id}",  name="voluntarioid")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
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
            if($voluntario=$this->voluntarioRepository->findOneBy(array('userid'=>$this->getrequest()->get('id')))){
                $data=$voluntario->objectToArray();
            }else
                $message=$this->getNoData();
        }elseif($voluntarios=$this->voluntarioRepository->findAll()) {
            foreach ($voluntarios as $voluntario){
                $data[]=$voluntario->objectToArray();
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
                        $voluntario = $this->voluntarioRepository->findOneBy(array('userid' => $user->getId()));
                        if(!$voluntario){
                            $this->jsonError('Voluntario no existe');
                        }else{
                            $voluntario=$this->fillVoluntario($voluntario);
                            $voluntario->setUserid($user);
                            if ($this->voluntarioRepository->saveVoluntario($voluntario)) {
                                $this->voluntarioRepository->getEntityManagerTransaction()->commit();
                                $this->userRepository->getEntityManagerTransaction()->commit();
                                $voluntario=$this->voluntarioRepository->findOneBy(array('id'=>$voluntario->getId()));
                                $data=$voluntario->objectToArray();
                                $this->jsonSuccess($data);
                            }else {
                                $this->voluntarioRepository->getEntityManagerTransaction()->rollback();
                                $this->userRepository->getEntityManagerTransaction()->rollback();
                            }
                        }
                    }
                }
            }else{
                $this->jsonError('Este usuario no existe no se puede actualizar');
            }
        }else
            {
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
                if($this->findInrequest(['nombreUsuario','contrasenia'])){
                    $this->getrequest()->attributes->set('roles', ['ROLE_VOLUNTARIO']);
                    $this->getrequest()->attributes->set('usernameIdUnique', $this->getrequest()->get('nombreUsuario'));
                    $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    $user = $this->fillUser(new User());
                    $user->setDatecreate(new \DateTime());
                    if($this->userRepository->insertUser($user)){
                        if($this->findInrequest(['nombre','apellidos','telefono','ambitoRecogida','ambitoEntrega','lopd'])){
                            $voluntario = $this->fillVoluntario(new Voluntario());
                            $voluntario->setUserid($user)->setFecha(new \DateTime());
                            if($this->voluntarioRepository->insertVoluntario($voluntario)){
                                $this->voluntarioRepository->getEntityManagerTransaction()->commit();
                                $this->userRepository->getEntityManagerTransaction()->commit();
                                $voluntario=$this->voluntarioRepository->findOneBy(array('id'=>$voluntario->getId()));
                                $data=$voluntario->objectToArray();
                                $this->jsonSuccess($data);
                            }else {
                                $this->voluntarioRepository->getEntityManagerTransaction()->rollback();
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

    /**
     * @param Voluntario $voluntario
     * @return Voluntario
     */
    private function fillVoluntario(Voluntario $voluntario){

        $voluntario->setNombre($this->findInrequest('nombre')?$this->getrequest()->get('nombre'):$voluntario->getNombre());
        $voluntario->setApellidos($this->findInrequest('apellidos')?$this->getrequest()->get('apellidos'):$voluntario->getApellidos());
        $voluntario->setTelefono($this->findInrequest('telefono')?$this->getrequest()->get('telefono'):$voluntario->getTelefono());
        $voluntario->setAmbitoRecogida($this->findInrequest('ambitoRecogida')?$this->getrequest()->get('ambitoRecogida'):$voluntario->getAmbitoRecogida());
        $voluntario->setAmbitoEntrega($this->findInrequest('ambitoEntrega')?$this->getrequest()->get('ambitoEntrega'):$voluntario->getAmbitoEntrega());
        $voluntario->setLopd(($this->findInrequest('lopd')&& $this->notEmptyOnrequest('lopd'))?filter_var(    $this->getrequest()->get('lopd'), FILTER_VALIDATE_BOOLEAN):$voluntario->getLopd());



       /* $provincia = $this->provinciaRepository->findOneBy(['id'=>$this->getrequest()->get('provincia')]);
        if($provincia){
            $voluntario->setProvincia($provincia);
        }

        $municipio = $this->municipioRepository->findOneBy(['id'=>$this->getrequest()->get('municipio')]);
        if($municipio){
            $voluntario->setMunicipio($municipio);
        }
       */
        return $voluntario;

    }


    public function fillUser(User $user)
    {
        return parent::fillUser($user); // TODO: Change the autogenerated stub
    }


    public function index(){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('voluntario/index.html.twig', [
            'controller_name' => 'VoluntarioController',
        ]);
    }

}
