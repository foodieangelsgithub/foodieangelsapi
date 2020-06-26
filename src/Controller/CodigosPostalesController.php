<?php

namespace App\Controller;



use App\Entity\CodigoPostal;
use App\Entity\Donacion;
use App\Entity\DonacionUser;
use App\Entity\Voluntario;
use App\Repository\BeneficiarioRepository;
use App\Repository\CodigoPostalRepository;
use App\Repository\DonacionUserRepository;
use App\Repository\VoluntarioRepository;
use App\Service\OneSignalNotification;
use App\Service\PushNotification;


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


    public function __construct(BeneficiarioRepository $beneficiarioRepository, VoluntarioRepository $voluntarioRepository,
                                CodigoPostalRepository $codigoPostalRepository, OneSignalNotification $oneSignalNotification,
                                PushNotification $pushNotification, DonacionUserRepository $donacionUserRepository){
        parent::__construct();
        $this->beneficiarioRepository   = $beneficiarioRepository;
        $this->voluntarioRepository     = $voluntarioRepository;
        $this->codigoPostalRepository   = $codigoPostalRepository;
        $this->pushNotification        = $pushNotification;

        $this->oneSignalNotification    = $oneSignalNotification;
        $this->donacionUserRepository = $donacionUserRepository;
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
        $codigoPostal = $this->codigoPostalRepository->findOneBy(['codigo'=>$codigoPostal]);

        $codigoPostalArray=$this->codigoPostalRepository->getQueryLatitu($codigoPostal->getLat(), $codigoPostal->getLon(), $distancia);

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
        return $this->getBeneficiariosCercanos('28220');
    }



    public function getBeneficiariosToSend($codigoPostal, $distancia=3, $distanciaMax=3){
        $donacionUsers=array();

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
                if($beneficiario->getUserid()->getId()==$this->getUser()->getId()){
                    $donacionUsers[]=$donacionUser;
                }
            }
        }else{
            $this->getBeneficiariosToSend($codigoPostal, ++$distancia, $distanciaMax);
        }

        if(count($donacionUsers)==0){
            return false;
        }else{
            return $donacionUsers;
        }
    }

    public function getVoluntariosToSend($codigoPostal, $distancia=3, $distanciaMax=3){
        $donacionUsers=array();

        if($codigos=$this->getCodigosPostales($codigoPostal, $distancia)) {

            $voluntarios=$this->voluntarioRepository->getAmbito($codigos);
            /**
             * @var $voluntario Voluntario
             */
            foreach ($voluntarios as $voluntario){

                if($this->getUser()->getId()==$voluntario->getUserid()->getId()) return true;
            }
        }else{
            $this->getVoluntariosToSend($codigoPostal, ++$distancia, $distanciaMax);
        }
        return false;
    }



    public function getBeneficiariosCercanos($codigoPostal, $distancia=3, $distanciaMax=3){
        $data=array();
        $message='';
        $dataEnvio=array();

        if($codigos=$this->getCodigosPostales($codigoPostal, $distancia))
        {

            $beneficiarios=$this->beneficiarioRepository->findBy(['codPostal'=>$codigos]);

            if($beneficiarios){
                foreach ($beneficiarios as $beneficiario){
                    $data[]=$beneficiario->objectToArrayExtra();
                    if($beneficiario->getUserid()->getOneSignalPlayerId()){
                        $dataEnvio[]=$beneficiario->getUserid()->getOneSignalPlayerId();
                    }
                }
            }else{
                $message=$this->getNoData();
            }
        }else {
            $message=$this->getNoData();
        }

        if(count($dataEnvio)>0){
            $data=array(
                'contents'=>['en'=>str_replace('-producto-',$this->getDonacion()->getProductId()->getNombre(), $this->getParameter('onesignalmessage.en.beneficiario.solicitado')), 'es'=>str_replace('-producto-',$this->getDonacion()->getProductId()->getNombre(), $this->getParameter('onesignalmessage.es.beneficiario.solicitado'))],
                'playerIds'=>$dataEnvio,
            );
            $data = $this->oneSignalSend($data);
        }else{
            if($distancia==$distanciaMax){
                $message=$this->getNoData();
            }else{
                return $this->getBeneficiariosCercanos($codigoPostal, ++$distancia, $distanciaMax);
            }
        }

        return $this->jsonSuccess($data, $message)->returnResponse();

    }

    public function getVoluntariosCercanos($codigoPostal, $distancia=3, $distanciaMax=3){
        $data=array();
        $message='';
        $dataEnvio=array();

        if($codigos=$this->getCodigosPostales($codigoPostal, $distancia))
        {

            $voluntarios=$this->voluntarioRepository->getAmbito($codigos);

            if($voluntarios){
                foreach ($voluntarios as $voluntario){
                    $data[]=$voluntario->objectToArrayExtra();
                    if($voluntario->getUserid()->getOneSignalPlayerId()){
                        $dataEnvio[]=$voluntario->getUserid()->getOneSignalPlayerId();
                    }
                }
            }else{
                $message=$this->getNoData();
            }
        }else {
            $message=$this->getNoData();
        }

        if(count($dataEnvio)>0){
            $data=array(
                'contents'=>['en'=>$this->getParameter('onesignalmessage.en.voluntario.recogida'), 'es'=>$this->getParameter('onesignalmessage.es.voluntario.recogida')],
                'playerIds'=>$dataEnvio,
            );
            $this->oneSignalSend($data);
        }else{
            if($distancia==$distanciaMax){
                $message=$this->getNoData();
            }else{
                return $this->getVoluntariosCercanos($codigoPostal, ++$distancia, $distanciaMax);
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