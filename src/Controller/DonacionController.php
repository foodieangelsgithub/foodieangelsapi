<?php

namespace App\Controller;

use App\Entity\Donacion;
use App\Entity\Producto;
use App\Entity\Servicio;
use App\Repository\DonacionUserRepository;
use App\Repository\DonacionRepository;
use App\Repository\IntegrantesRepository;
use App\Repository\ProductoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DonacionController extends BaseController
{


    public $donacionRepository;
    public $productoRepository;

    private $beneficiarioController;
    private $servicioController;

    private $codigosPostalesController;

    private $donacionUserRepository;

    public function __construct(DonacionRepository $donacionRepository, ProductoRepository $productoRepository,
                                ServicioController $servicioController, BeneficiarioController $beneficiarioController,
                                CodigosPostalesController $codigosPostalesController,
                                DonacionUserRepository $donacionUserRepository)
    {
        parent::__construct();
        $this->donacionRepository       = $donacionRepository;
        $this->productoRepository       = $productoRepository;

        $this->beneficiarioController   = $beneficiarioController;

        $this->servicioController       = $servicioController;

        $this->codigosPostalesController = $codigosPostalesController;

        $this->donacionUserRepository = $donacionUserRepository;
    }

    /**

     * @Route("/donacion", name="donacion")
     * @Route("/donacion/{id}", name="donacionid")
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

        if($this->getrequest() && $this->getrequest()->get('id') && !is_numeric($this->getrequest()->get('id'))) {
            if ($this->isGranted('ROLE_ADMIN')) {
                switch ($this->getrequest()->get('id')) {
                    case 'closed':
                        $donacions = $this->donacionRepository->forAdminClosed();
                        foreach ($donacions as $donacion) {
                            $data[] = $donacion->objectToArray();
                        }
                        break;
                    case 'open':
                        $donacions = $this->donacionRepository->forAdminOpen();
                        foreach ($donacions as $donacion) {
                            $data[] = $donacion->objectToArray();
                        }
                        break;
                    case 'cancel':
                        $donacions = $this->donacionRepository->forAdminCancel();
                        foreach ($donacions as $donacion) {
                            $data[] = $donacion->objectToArray();
                        }
                        break;
                    default:
                        $donacions = $this->donacionRepository->findBy(array(), array('fecha' => 'DESC'));
                        foreach ($donacions as $donacion) {
                            $data[] = $donacion->objectToArray();
                        }
                        break;
                }
            }
        }elseif ($this->getrequest() && $this->getrequest()->get('id')) {
            if ($donacion = $this->donacionRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $donacion->objectToArray();
            } else {
                $message=$this->getNoData();
            }
        }elseif( $this->isGranted('ROLE_BENEFICIARIO')) {

            $donacions = $this->donacionRepository->findBy(['estado'=>1]);
            if($donacions){
                foreach ($donacions as $donacion) {
                    if ($this->codigosPostalesController->setDonacion($donacion)->getBeneficiariosToSend($donacion->getProveedorId()->getCodPostal())==true) {
                        $data[] = $donacion->objectToArray();
                    }
                }
            } else {
                $message=$this->getNoData();
            }
        }elseif($this->isGranted('ROLE_VOLUNTARIO')){
            $donacions = $this->donacionRepository->greatherThan(-1);

            if($donacions){
                /**
                 * @var $donacion Donacion
                 */
                foreach ($donacions as $donacion) {

                    if ($this->codigosPostalesController->setDonacion($donacion)->getVoluntariosToSend($donacion->getProveedorId()->getCodPostal())==true) {
                            $data[] = $donacion->objectToArray();
                    }
                }
            } else {
                $message=$this->getNoData();
            }
        }elseif($this->isGranted('ROLE_ADMIN')){
            $donacions = $this->donacionRepository->findBy(array(), array('fecha' => 'DESC'));
            foreach ($donacions as $donacion) {
                $data[] = $donacion->objectToArray();
            }
        }

        if(count($data)==0){
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
        $data=array();
        $message='';
        if($this->notEmptyOnrequest('id') && $this->getrequest()->get('id')>0){

        }else{
            if($this->notEmptyOnrequest('producto') && $this->getrequest()->get('producto')>0 && $this->notEmptyOnrequest('cantidad') && $this->getrequest()->get('cantidad')>0){
                $donacion = $this->fillDonacion(new Donacion());
                if(!$donacion)
                {
                    $message=$this->getNoData('error');
                }else{
                    $donacion->setFecha(new \DateTime());
                    if($this->donacionRepository->saveDonacion($donacion)){
                        $this->donacionRepository->getEntityManagerTransaction()->commit();
                        $data=$donacion->objectToArray();
                        /**
                         * configuramos donacion_id para poder llamar al servicio y crear uno nuevo
                         */
                        $this->getrequest()->attributes->set('id', null);
                        $this->getrequest()->attributes->set('donacion_id', $data['id']);


                        /**
                         * Enviamos una alerta de servicio a los móviles
                         */
                        $this->codigosPostalesController->setDonacion($donacion)->getBeneficiariosCercanos($donacion->getProveedorId()->getCodPostal());

                        // $this->getrequest()->request->replace(['donacion_id'=>$donacion->getId(), 'cantidad'=>0]);
                        // $this->servicioController->setrequest($this->getrequest(),0)->setServicio(new Servicio());
                    }else{
                        $message=$this->getNoData('no insert');
                    }
                }

            } else{
                $message=$this->getNoData('error');
            }

        }

        $this->jsonSuccess($data, $message);

        return $this;
    }



    ///////https://ourcodeworld.com/articles/read/1019/how-to-find-nearest-locations-from-a-collection-of-coordinates-latitude-and-longitude-with-php-mysql//////

    /**
     * @param Donacion $donacion
     * @return Donacion|bool
     */
    private function fillDonacion(Donacion $donacion){


        if($this->findInrequest('producto'))
        {
            $producto=$this->productoRepository->findOneBy(['id'=>$this->getrequest()->get('producto')]);
            if(!$producto){
                return false;
            }
        }


        $donacion->setProductId($this->findInrequest('producto')?$producto:$donacion->getProductId())
            ->setProveedorId($this->getUser()->getProveedor())
            ->setCantidad($this->findInrequest('cantidad')?$this->getrequest()->get('cantidad'):$donacion->getCantidad())
            ->setTotal($this->findInrequest('cantidad')?$this->getrequest()->get('cantidad'):$donacion->getTotal())
            ->setEstado(1)
        ;

        return $donacion;


    }


}