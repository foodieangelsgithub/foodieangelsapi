<?php

namespace App\Controller;




use App\Repository\MunicipioRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MunicipioController extends BaseController
{


    public function __construct(MunicipioRepository $municipioRepository)
    {
        parent::__construct();
        $this->municipioRepository = $municipioRepository;
    }

    /**
     * @Route("/municipio", name="municipio")
     * @Route("/municipio/{id}", name="municipioid")
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

        if ($this->getrequest() && $this->getrequest()->get('id')) {
            if ($municipio = $this->municipioRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $municipio->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($municipios = $this->municipioRepository->findAll()) {
            foreach ($municipios as $municipio) {
                if(($municipio->getProvinciaId()) && $municipio->getProvinciaId()->getId()==2295){
                    $data[] = $municipio->objectToArray();
                }
            }
        } else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data,$message);
        return $this;
    }

    private function insertUpdate(){
        return $this;
    }
}