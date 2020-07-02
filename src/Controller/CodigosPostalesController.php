<?php

namespace App\Controller;



use App\Entity\Beneficiario;
use App\Entity\CodigoPostal;
use App\Entity\Donacion;
use App\Entity\DonacionUser;
use App\Entity\Voluntario;
use App\Model\Distancia;
use App\Repository\BeneficiarioRepository;
use App\Repository\CodigoPostalRepository;
use App\Repository\DonacionUserRepository;
use App\Repository\VoluntarioRepository;
use App\Service\OneSignalNotification;
use App\Service\PushNotification;


use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CodigosPostalesController extends BaseController
{
    protected $beneficiarioRepository;
    protected $voluntarioRepository;
    protected $codigoPostalRepository;
    protected $pushNotification;

    protected $oneSignalNotification;
    protected $donacion;

    private $donacionUserRepository;

    /**
     * @var Distancia
     */
    protected $distancia;


    public function __construct(ContainerInterface $container, BeneficiarioRepository $beneficiarioRepository, VoluntarioRepository $voluntarioRepository,
                                CodigoPostalRepository $codigoPostalRepository, OneSignalNotification $oneSignalNotification,
                                PushNotification $pushNotification, DonacionUserRepository $donacionUserRepository){
        parent::__construct();
        $this->beneficiarioRepository   = $beneficiarioRepository;
        $this->voluntarioRepository     = $voluntarioRepository;
        $this->codigoPostalRepository   = $codigoPostalRepository;
        $this->pushNotification        = $pushNotification;

        $this->oneSignalNotification    = $oneSignalNotification;
        $this->donacionUserRepository = $donacionUserRepository;

        $this->container=$container;
        $this->distancia = new Distancia();
        $this->distancia
            ->setDistanciaMaxV($this->getParameter('max.distance.voluntarios'))
            ->setDistanciaMinV($this->getParameter('min.distance.voluntarios'))
            ->setCantidadV($this->getParameter('max.cant.voluntarios'))
            ->setDistanciaMaxB($this->getParameter('max.distance.beneficiarios'))
            ->setDistanciaMinB($this->getParameter('min.distance.beneficiarios'))
            ->setCantidadB($this->getParameter('max.cant.beneficiarios'));
    }



    /**
     * @param $donacion Donacion
     * @return $this
     */
    public function setDonacion($donacion):self
    {
        $this->donacion=$donacion;
        return $this;
    }

    /**
     * @return Donacion
     */
    public function getDonacion(){
        return $this->donacion;
    }


    public function getCodigosPostales($codigoPostal, $distancia){
        $codigos=[];
        $codigoPostal = $this->codigoPostalRepository->findOneBy(['codigo'=>$codigoPostal]);

        if($codigoPostal){
            $codigoPostalArray=$this->codigoPostalRepository->getQueryLatitu($codigoPostal->getLat(), $codigoPostal->getLon(), $distancia);
        }

        /**
         * @var $cod CodigoPostal
         */
        if($codigoPostalArray) {
            foreach ($codigoPostalArray as $cod) {
                if (is_array($cod)) {
                    $codigos[] = $cod[0]->getCodigo();
                }
            }
        }

        return $codigos;
    }


    /**
     * @Route("/codigo/envio", name="codenvio")
     */
    public function pruebaEnvio(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->getVoluntariosCercanos('28030');
    }



    public function getBeneficiariosToSend($codigoPostal){

        $distancia=$this->distancia->getDistanciaMinB();

        if($distancia < $this->distancia->getDistanciaMaxB()){
            if($codigos=$this->getCodigosPostales($codigoPostal, $distancia)) {
                $beneficiarios = $this->beneficiarioRepository->findBy(['codPostal' => $codigos]);

                foreach ($beneficiarios as $beneficiario){
                    if(!$donacionUser = $this->donacionUserRepository->findOneBy(['user'=>$beneficiario->getUserid(), 'donacion'=>$this->donacion])){
                        $donacionUser=new DonacionUser();
                    }

                    $donacionUser->setUser($beneficiario->getUserid())
                        ->setDonacion($this->donacion);
                    $this->donacionUserRepository->saveData($donacionUser);
                    $this->donacionUserRepository->getEntityManagerTransaction()->commit();

                    if($beneficiario->getUserid()->getId()==$this->getUser()->getId()) return true;
                }
            }else{
                $this->distancia->setDistanciaMinB(++$distancia);
                $this->getBeneficiariosToSend($codigoPostal);
            }
        }

        return false;
    }

    public function getVoluntariosToSend($codigoPostal){

        $distancia=$this->distancia->getDistanciaMinV();

        if($distancia < $this->distancia->getDistanciaMaxV()) {
            if ($codigos = $this->getCodigosPostales($codigoPostal, $distancia)) {

                $voluntarios = $this->voluntarioRepository->getAmbito($codigos);

                foreach ($voluntarios as $voluntario) {
                    if ($this->getUser()->getId() == $voluntario->getUserid()->getId()) return true;
                }
            } else {
                $this->distancia->setDistanciaMinV(++$distancia);
                $this->getVoluntariosToSend($codigoPostal);
            }
        }
        return false;
    }



    public function getBeneficiariosCercanos($codigoPostal, $dataEnvio=array()){
        $data=array();
        $message='';

        $distancia=$this->distancia->getDistanciaMinB();

        if($distancia >= $this->distancia->getDistanciaMaxB() || $this->distancia->getCantidadMaxB() < $this->distancia->getCantidadActualB()){

            if(count($dataEnvio)>0){
                $data=array(
                    'contents'=>['en'=>str_replace('-producto-',$this->getDonacion()->getProductId()->getNombre(), $this->getParameter('onesignalmessage.en.beneficiario.solicitado')), 'es'=>str_replace('-producto-',$this->getDonacion()->getProductId()->getNombre(), $this->getParameter('onesignalmessage.es.beneficiario.solicitado'))],
                    'playerIds'=>$dataEnvio,
                );
                $data = $this->oneSignalSend($data);
            }else{
                $message=$this->getNoData();
            }
        }else{
            if ($codigos = $this->getCodigosPostales($codigoPostal, $distancia)) {
                $beneficiarios = $this->beneficiarioRepository->findBy(['codPostal' => $codigos]);

                if ($beneficiarios) {

                    foreach ($beneficiarios as $beneficiario) {
                        $data[] = $beneficiario->objectToArrayExtra();
                        if ($beneficiario->getUserid()->getOneSignalPlayerId()) {
                            $dataEnvio[] = $beneficiario->getUserid()->getOneSignalPlayerId();
                        }

                    }
                }
            }
            $dataEnvio=array_unique($dataEnvio);
            $this->distancia->setCantidadActualB(count($dataEnvio))
                ->setDistanciaMinB(++$distancia);
            return $this->getBeneficiariosCercanos($codigoPostal, $dataEnvio);
        }
        return $this->jsonSuccess($data, $message)->returnResponse();
    }

    public function getVoluntariosCercanos($codigoPostal, $dataEnvio=array()){
        $data=array();
        $message='';

        $distancia=$this->distancia->getDistanciaMinV();

        if($distancia >= $this->distancia->getDistanciaMaxV() || $this->distancia->getCantidadMaxV() < $this->distancia->getCantidadActualV()){

            if(count($dataEnvio)>0){

                $data=array(
                    'contents'=>['en'=>$this->getParameter('onesignalmessage.en.voluntario.recogida'), 'es'=>$this->getParameter('onesignalmessage.es.voluntario.recogida')],
                    'playerIds'=>$dataEnvio,
                );
                $this->oneSignalSend($data);
            }else{
                $message=$this->getNoData();
            }
        }else{
            if ($codigos = $this->getCodigosPostales($codigoPostal, $distancia)) {
                $voluntarios = $this->voluntarioRepository->getAmbito($codigos);

                if ($voluntarios) {

                    foreach ($voluntarios as $voluntario) {
                        $data[] = $voluntario->objectToArrayExtra();
                        if ($voluntario->getUserid()->getOneSignalPlayerId()) {
                            $dataEnvio[] = $voluntario->getUserid()->getOneSignalPlayerId();
                        }
                    }
                }
            }
            $dataEnvio=array_unique($dataEnvio);
            $this->distancia->setCantidadActualV(count($dataEnvio))
                ->setDistanciaMinV(++$distancia);
            return $this->getVoluntariosCercanos($codigoPostal, $dataEnvio);
        }
        return $this->jsonSuccess($data, $message)->returnResponse();
    }

    public function _getVoluntariosCercanos($codigoPostal, $dataEnvio){
        $data=array();
        $message='';
        $dataEnvio=array();
        $distancia=$this->distancia->getDistanciaMinB();

        if($distancia < $this->distancia->getDistanciaMaxV() && $this->distancia->getCantidadMaxV() > $this->distancia->getCantidadActualV()) {
            if ($codigos = $this->getCodigosPostales($codigoPostal, $distancia)) {

                $voluntarios = $this->voluntarioRepository->getAmbito($codigos);

                if ($voluntarios) {
                    foreach ($voluntarios as $voluntario) {
                        $data[] = $voluntario->objectToArrayExtra();
                        if ($voluntario->getUserid()->getOneSignalPlayerId()) {
                            $dataEnvio[] = $voluntario->getUserid()->getOneSignalPlayerId();
                        }
                        $this->distancia->setCantidadActualV($this->distancia->getCantidadActualV()+count($dataEnvio));
                    }
                } else {
                    $message = $this->getNoData();
                }
            } else {
                $message = $this->getNoData();
            }
        }

        if(count($dataEnvio)>0){
            $data=array(
                'contents'=>['en'=>$this->getParameter('onesignalmessage.en.voluntario.recogida'), 'es'=>$this->getParameter('onesignalmessage.es.voluntario.recogida')],
                'playerIds'=>$dataEnvio,
            );
            $this->oneSignalSend($data);
        }else{
            if($distancia>=$this->distancia->getDistanciaMaxV()){
                $message=$this->getNoData();
            }else{
                $this->distancia->setDistanciaMinV(++$distancia);
                return $this->getVoluntariosCercanos($codigoPostal, $dataEnvio);
            }
        }

        return $this->jsonSuccess($data, $message)->returnResponse();
    }


    public function oneSignalSend(array $array){

        if(count($array['playerIds'])>0 && $array['playerIds'][0]!=''){
            return $this->oneSignalNotification->setValues($array)->send();
        }
        return true;

    }


    public function enviar($dataEnvio, $mensaje, $titulo){
        $this->pushNotification->setTitle($titulo)
            ->setPlataform(1)
            ->setBody($mensaje)
            ->setRecipientsId($dataEnvio)
            ->setTopic('Envio_Foodie');
        $this->pushNotification->enviarDatos();
    }

}