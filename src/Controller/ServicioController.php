<?php

namespace App\Controller;

use App\Entity\Servicio;
use App\Entity\User;
use App\Helper\ApiUploadedFile;
use App\Repository\BeneficiarioRepository;
use App\Repository\DonacionRepository;
use App\Repository\ServicioRepository;
use App\Repository\UserRepository;
use App\Repository\VoluntarioRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\PushNotification;
use App\Service\OneSignalNotification;

class ServicioController extends BaseController
{


    protected $servicioRepository;
    protected $beneficiarioRepository;
    protected $voluntarioRepository;
    protected $donacionRepository;
    private $fileUploader;
    protected $pushNotification;
    protected $codigosPostalesController;

    protected $oneSignalNotification;


    public function __construct(ServicioRepository $servicioRepository, BeneficiarioRepository $beneficiarioRepository, VoluntarioRepository $voluntarioRepository,
                                DonacionRepository $donacionRepository, FileUploader $fileUploader, CodigosPostalesController $codigosPostalesController,
                                OneSignalNotification $oneSignalNotification, UserRepository $userRepository)
    {
        parent::__construct();
        $this->servicioRepository = $servicioRepository;
        $this->beneficiarioRepository=$beneficiarioRepository;
        $this->voluntarioRepository=$voluntarioRepository;
        $this->donacionRepository=$donacionRepository;
        $this->fileUploader=$fileUploader;

        $this->codigosPostalesController=$codigosPostalesController;
        $this->oneSignalNotification    = $oneSignalNotification;
        $this->userRepository   = $userRepository;
    }

    /**
     * @Route("/servicio/final", name="serviciofinal")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function finalAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->setrequest($request);
        $data=array();
        $message='';

            if ($servicios = $this->servicioRepository->findBy(array('estado' => 5))) {
                foreach ($servicios as $servicio) {
                    $data[] = $servicio->objectToArray();
                }
            } else
                $message=$this->getNoData();

        $this->jsonSuccess($data, $message);

        return $this->returnResponse();
    }

    /**
     * @Route("/servicio/user", name="serviciouser")
     * Esto muestra los servicios del usuario logado y el tipo de usuario
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function servicioPersonal(){
        //$this->denyAccessUnlessGranted('ROLE_USER');

        $user   = $this->userRepository->findOneBy(['id'=>$this->getUser()->getId()]);

        $servicio=new Servicio();
        if($user->getBeneficiarios()->count()>0){
            $servicio = $this->servicioRepository->findOneByEstadoBeneficiario(5, $user->getBeneficiarios()[0]->getId());
        }elseif($user->getVoluntarios()->count()>0){
            $servicio = $this->servicioRepository->findOneByEstadoVoluntario(5,$user->getVoluntarios()[0]->getId());
        }

        if($servicio){
            $data=$servicio[0]->objectToArray();
            $data['rol']=($user->getRoles());
        }else{
            $data=[];
        }
        return $this->jsonSuccess($data)->returnResponse();
        //if($this->servicioRepository->findBy(['beneficiario'=>]))

    }

    /**
     * @Route("/servicio/cancel", name="serviciocancel")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function cancelServicio(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->setrequest($request);

        if ($this->notEmptyOnrequest('servicio_id') && $this->getrequest()->get('servicio_id') >0){
            $servicio = $this->servicioRepository->findOneBy(['id'=>$this->getrequest()->get('servicio_id')]);

            if($servicio && $servicio->getVoluntario() && $servicio->getVoluntario()->getUserid()->getId()==$this->getUser()->getId()){
                $donacion = $servicio->getDonacion();
                if($donacion->getTotal()==0){
                    $donacion->setEstado(2);
                }
                $this->codigosPostalesController->getVoluntariosCercanos($donacion->getProveedorId()->getCodPostal());

                $data=array(
                    'contents'=>['en'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.en.proveedor.cancel')), 'es'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.es.proveedor.cancel'))],
                    'playerIds'=>[$servicio->getDonacion()->getProveedorId()->getUserid()->getOneSignalPlayerId()],
                );

                $this->codigosPostalesController->oneSignalSend($data);

                $servicio->setEstado(2)->setVoluntario(null);
                $this->servicioRepository->insertServicio($servicio);
                $this->servicioRepository->getEntityManagerTransaction()->commit();
                $this->jsonSuccess('Se ha cancelado su recogida con éxito');

            }else{
                $this->jsonError('No tiene permiso para realizar esta acción');
            }

        }else{
            $this->jsonError('No puede realizar esta acción');
        }

        return $this->returnResponse();
    }
    /**
     * @Route("/servicio", name="servicio")
     * @Route("/servicio/{id}", name="servicioid")
     *
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
        if ($this->getrequest() && $this->getrequest()->get('id')) {
            if ($servicio = $this->servicioRepository->findOneBy(array('id' => $this->getrequest()->get('id')))) {
                $data = $servicio->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($servicios = $this->servicioRepository->findAll()) {
            foreach ($servicios as $servicio) {
                if($this->getUser()->isBeneficiario()){
                    if($servicio->getEstado()==1){
                        $data[] = $servicio->objectToArray();
                    }
                }elseif($this->getUser()->isVoluntario()){
                    if($servicio->getEstado()==2){
                        $data[] = $servicio->objectToArray();
                    }
                }elseif($this->getUser()->isAdmin()){
                    $data[] = $servicio->objectToArray();
                }

            }
        } else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data, $message);
        return $this;
    }



    /**
     * @return $this
     * @throws \Exception
     */
    private function insertUpdate(){


        if($this->notEmptyOnrequest('servicio_id') && $this->getrequest()->get('servicio_id')>0){

            if ($servicio = $this->servicioRepository->findOneBy(array('id' => $this->getrequest()->get('servicio_id')))) {

                $servicio = $this->fillServicio($servicio);


                if($servicio){
                    $servicio->setFechaModi(new \DateTime());
                    if($this->servicioRepository->insertServicio($servicio)){
                        $donacion=$servicio->getDonacion();
                        if($servicio->getEstado()==2) {
                            $donacion->setTotal($donacion->getCantidad() - $servicio->getCantidad());
                        }
                        if($donacion->getTotal()==0){
                            $donacion->setEstado($servicio->getEstado());
                        }elseif($servicio->getEstado()<3){
                            $donacion->setEstado($servicio->getEstado());
                        }

                        $this->donacionRepository->saveDonacion($donacion);
                        $this->servicioRepository->getEntityManagerTransaction()->commit();
                        $this->donacionRepository->getEntityManagerTransaction()->commit();

                        $data=$servicio->objectToArray();

                        if($servicio->getEstado()==2){
                            //CREAR ALERTA DE BENEFICIARIO AL VOLUNTARIO
                            $this->codigosPostalesController->setDonacion($donacion)->getVoluntariosCercanos($donacion->getProveedorId()->getCodPostal());
                        }
                        //Mensaje al Proveedor de recogida
                        elseif($servicio->getEstado()==3){
                            $data=array(
                                'contents'=>['en'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.en.proveedor.recogida')), 'es'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.es.proveedor.recogida'))],
                                'playerIds'=>[$servicio->getDonacion()->getProveedorId()->getUserid()->getOneSignalPlayerId()],
                            );
                            $this->codigosPostalesController->oneSignalSend($data);
                        }//Mensaje al Beneficiario de recogida
                        elseif($servicio->getEstado()==4){
                            $data=array(
                                'contents'=>['en'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.en.beneficiario.entrega')), 'es'=>str_replace('-USER-', ($servicio->getVoluntario()->getNombre().' '.$servicio->getVoluntario()->getApellidos()), $this->getParameter('onesignalmessage.es.beneficiario.entrega'))],
                                'playerIds'=>[$servicio->getBeneficiario()->getUserid()->getOneSignalPlayerId()],
                            );
                            $this->codigosPostalesController->oneSignalSend($data);
                        }//Mensaje al Proveedor de entrega
                        elseif($servicio->getEstado()==5){
                            if($donacion->getTotal()==0){
                                $parte='';
                            }else{
                                $parte=' una parte de ';
                            }
                            $data=array(
                                'contents'=>['en'=>str_replace('-PARTE-',$parte, $this->getParameter('onesignalmessage.en.proveedor.entrega')), 'es'=>str_replace('-PARTE-',$parte, $this->getParameter('onesignalmessage.es.proveedor.entrega'))],
                                'playerIds'=>[$donacion->getProveedorId()->getUserid()->getOneSignalPlayerId()],
                            );
                            $this->codigosPostalesController->oneSignalSend($data);
                        }

                        $this->jsonSuccess($data);
                    }
                }else{
                    if($this->servicioRepository->getMessage()!=''){
                        $data=$this->servicioRepository->getMessage();
                        $this->jsonError($data);
                    }else{
                        $data=$this->getNoData('nodonacion');
                        $this->jsonError($data);
                    }

                }
            }else{
                $data=$this->getNoData('error');
                $this->jsonSuccess($data);
            }

        }
        else{

            $this->setServicio(new Servicio());

        }

        return $this;
    }


    public function setServicio(Servicio $servicio){
        $servicio = $this->fillServicio($servicio);

        if($servicio){
            $servicio->setFecha(new \DateTime());
            if($this->servicioRepository->insertServicio($servicio)){
                $donacion=$servicio->getDonacion();

                $donacion->setTotal( $donacion->getTotal()-$servicio->getCantidad());
                if($donacion->getTotal()==0){
                    $donacion->setEstado($servicio->getEstado());
                }
                $this->donacionRepository->saveDonacion($donacion);
                $this->servicioRepository->getEntityManagerTransaction()->commit();
                $this->donacionRepository->getEntityManagerTransaction()->commit();

                $data=$servicio->objectToArray();
                $this->jsonSuccess($data);

                if($servicio->getEstado()==2){
                    //CREAR ALERTA DE BENEFICIARIO AL VOLUNTARIO
                    $this->codigosPostalesController->setDonacion($donacion)->getVoluntariosCercanos($donacion->getProveedorId()->getCodPostal());
                }
            }
        }else{
            if($this->servicioRepository->getMessage()!=''){
                $data=$this->servicioRepository->getMessage();
                $this->jsonError($data);
            }else{
                $data=$this->getNoData('error');
                $this->jsonError($data);
            }
        }
    }

    /**
     * @param Servicio $servicio
     * @return Servicio|bool
     */
    private function fillServicio(Servicio $servicio){


        if($this->getrequest()->attributes->get('donacion_id')){
            $donacionId=$this->getrequest()->attributes->get('donacion_id');
        }elseif ($this->findInrequest('donacion_id')){
            $donacionId=$this->getrequest()->get('donacion_id');
        }
        if($donacionId)
        {
            $donacion=$this->donacionRepository->findOneBy(['id'=>$donacionId]);
            $estado=1;
        }

        if(!isset($donacion) || !$donacion){
            $this->servicioRepository->setMessage('Donación no existe');
            return false;
        }


        if($this->findInrequest('beneficiario_id') && $this->getrequest()->get('beneficiario_id')>0)
        {
            $estado=2;
            if($this->getUser()->getId()==$this->getrequest()->get('beneficiario_id')){
                if($servicio->getBeneficiario()){
                    $this->servicioRepository->setMessage('Esta donación ya la ha aceptado otro beneficiarion');
                }else{
                    $beneficiario=$this->beneficiarioRepository->findOneBy(['userid'=>$this->getrequest()->get('beneficiario_id')]);

                }

                if(!$beneficiario){
                    $this->servicioRepository->setMessage('Usted no tiene permiso');
                    return false;
                }
            }else{
                $this->servicioRepository->setMessage('Usted no tiene permiso para realizar esta acción');
                return false;
            }

        }

        if($this->findInrequest('voluntario_id') && $this->getrequest()->get('voluntario_id')>0)
        {

            if($this->getUser()->getId()==$this->getrequest()->get('voluntario_id')) {
                if($servicio->getVoluntario() && $servicio->getVoluntario()->getUserid()->getId()!=$this->getUser()->getId()){
                    $this->servicioRepository->setMessage('Esta donación ya la ha aceptado otro voluntario');
                    return false;
                }else{
                    $voluntario = $this->voluntarioRepository->findOneBy(['userid' => $this->getrequest()->get('voluntario_id')]);
                    $estado = 3;
                    if($servicio->getEstado()==3 || $servicio->getEstado()==4){
                        $estado = 4;
                    }
                    if (!$voluntario) {
                        $this->servicioRepository->setMessage('Usted no tiene permiso');
                        return false;
                    }
                }
            }else{
                $this->servicioRepository->setMessage('Usted no tiene permiso');
                return false;
            }
        }



        if($foto = $this->getRequest()->get('foto')) {

            $file = new ApiUploadedFile($foto, $this->getParameter('directory_images'));
            $file->moveImage();
            if($this->getUser()->getVoluntarios() && $this->getUser()->getVoluntarios()[0]->getId()) {

                $rutaFoto = "{$this->getParameter('directory_images')}/{$file->getFileName()}";
                $estado = 5;
                $servicio->setRutaFoto($rutaFoto);
            }else{
                return false;
            }
        }


        /**
         * Si la cantidad que queda es menor que la solicitada devuelve error
         *
         */
        if($donacion->getTotal()<$this->getrequest()->get('cantidad')){
            if($donacion->getTotal()==0){
                $this->servicioRepository->setMessage('Ya no queda nada disponible de esta donación');
            }else{
                $this->servicioRepository->setMessage("Solo queda disponible {$donacion->getTotal()}kg de {$donacion->getProductId()->getNombre()}");
            }

            return false;
        }


        $servicio->setDonacion($donacionId?$donacion:$servicio->getDonacion())
            ->setBeneficiario($this->findInrequest('beneficiario_id')?$beneficiario:$servicio->getBeneficiario())
            ->setVoluntario($this->findInrequest('voluntario_id')?$voluntario:$servicio->getVoluntario())
            ->setCantidad($this->findInrequest('cantidad')?$this->getrequest()->get('cantidad'):$servicio->getCantidad())
            ->setEstado($estado)
            ;

        return $servicio;
    }





}