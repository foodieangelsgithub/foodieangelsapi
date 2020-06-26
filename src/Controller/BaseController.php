<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\MunicipioRepository;
use App\Repository\PaisRepository;
use App\Repository\ProvinciaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{

    private $jsonResponse;
    /**
     * @var $request Request
     */
    private $request;
    /**
     * @var $provinciaRepository ProvinciaRepository
     */
    public $provinciaRepository;
    /**
     * @var $municipioRepository MunicipioRepository
     */
    public $municipioRepository;
    /**
     * @var $paisRepository PaisRepository
     */
    public $paisRepository;


    public $elementNotFound;
    /**
     * @var $userRepository UserRepository
     */
    public $userRepository;

    public function __construct()
    {

    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setrequest(Request $request, $set=1)
    {

        $data = $request->request->all();
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json') && $set==1) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
        $this->request=$request;
        return $this;
    }

    /**
     * @return Request
     */
    public function getrequest()
    {
       return $this->request;
    }




    /**
     * @param $message
     * @return $this
     */
    public function jsonError($message)
    {
        $this->jsonResponse = ['status' => 'error', 'message' => $message];

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function jsonSuccess($data, $message='')
    {
        $this->jsonResponse = ['status' => 'success', 'data' => $data, 'message'=>$message];

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function returnResponse() {

        $response = new JsonResponse($this->jsonResponse, Response::HTTP_OK);
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );

        return $response;
    }


    /**
     * Find if value exist on request
     * @param $elementsToFind
     * @return bool
     */
    public function findInrequest( $elementsToFind) {
        if(!is_array($elementsToFind)){
            $elementsFind[]=$elementsToFind;
        }else{
            $elementsFind=$elementsToFind;
        }

        foreach ($elementsFind as $element) {
            if($this->getrequest()->get($element)===NULL) {
                $this->elementNotFound= $element;
                return false;
            }
        }
        return true;
    }

    /**
     * Find if value exist on request and it's not empty
     * @param $elementsToFind
     * @return bool
     */
    public function notEmptyOnrequest( $elementsToFind) {
        if(!is_array($elementsToFind)){
            $elementsFind[]=$elementsToFind;
        }else{
            $elementsFind=$elementsToFind;
        }

        foreach ($elementsFind as $element) {
            if($this->getrequest()->get($element)==='' || !$this->findInrequest($element)) {
                $this->elementNotFound= $element;
                return false;
            }
        }
        return true;
    }

    /**
     * @param $array
     * @param $val
     * @return bool
     */
    public function findInArray($array, $val){
        if(in_array($val, $array)){
            return true;
        }
        return false;
    }

    /**
     * @param $attributes
     * @param null $subject
     * @param string $message
     */
    public function denyAccessUnlessGranted($attributes, $subject = null, $message = 'Access Denied.'): void{
       // return ;
        parent::denyAccessUnlessGranted($attributes, $subject,  $message);
    }


    /**
     * @param User $user
     * @return User|bool
     * @throws \Exception
     */
    public function fillUser(User $user){
        $user->setActive($this->findInrequest('active')&&is_numeric($this->getrequest()->get('active')) ?$this->getrequest()->get('active'):intval($user->getActive()+0));
        $user->setEmail($this->findInrequest('email')?$this->getrequest()->get('email'):$user->getEmail());
        $user->setName($this->findInrequest('nombre')?$this->getrequest()->get('nombre'):$user->getName());
        $user->setSurname($this->findInrequest('apellidos')?$this->getrequest()->get('apellidos'):$user->getSurname());
        $user->setTelephone($this->findInrequest('telefono')?$this->getrequest()->get('telefono'):$user->getTelephone());
        $user->setRoles($this->findInrequest('roles')?$this->getrequest()->get('roles'):$user->getRoles());




        if($this->findInrequest('usernameIdUnique')){
            $user->setUsername($this->getrequest()->get('usernameIdUnique'));
        }
        if($this->findInrequest('passwordIdUnique')){
            if(strlen($this->getrequest()->get('passwordIdUnique'))<8){
                $this->userRepository->setMessage('Password debe tener al menos 8 caracteres');
                return false;
            }
            $password=$this->userRepository->passwordEncoder->encodePassword(
                $user,
                $this->getrequest()->get('passwordIdUnique')
            );
            $user->setPassword($password);
            $opciones = [
                'cost'=>12
            ];
            $user->setApiToken(bin2hex(password_hash(bin2hex(random_bytes(60)).$user->getId(), PASSWORD_BCRYPT, $opciones)."\n"));
        }
        return $user;
    }


    public function getNoData($id=0){
        $data=array(
            0           => "No se encontraron datos",
            1           => "No se encontró el producto",
            'error'     => "Faltan datos",
            'no insert' => "Ha habido un error al insertar/actualizar",
            'nodonacion'=> "No existe esa donación/servicio"
        );
        try{
            return $data[$id];
        }catch (\Exception $e){
            return $data[0];
        }

    }



    public function getPostalCode(){
        /**
         * SELECT * FROM (
        SELECT A.lat, A.lon, CP.codigo, A.poblacion_id, A.municipio_id, A.provincia_id, A.nombre_via, A.via_id,
        (
        (
        (
        acos(
        sin(( 38.03943900000000 * pi() / 180))
         *
        sin(( A.lat * pi() / 180)) + cos(( 38.03943900000000 * pi() /180 ))
         *
        cos(( A.lat * pi() / 180)) * cos((( -1.24420200000000 - A.lon) * pi()/180)))
        ) * 180/pi()
        ) * 60 * 1.1515
        )
        as distance FROM  Activo A  INNER JOIN RelViaCodigopostal RVC on RVC.via_id=A.via_id
        INNER JOIN Codigopostal CP on CP.id=RVC.codigopostal_id order by A.via_id
        ) myTable
        WHERE distance <= 1
        order by  via_id
        LIMIT 15;
         */
    }

}
