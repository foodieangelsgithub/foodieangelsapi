<?php

namespace App\Controller;


use App\Repository\ProcIngresosRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProcIngresosController extends BaseController
{

    private $procIngresosRepository;


    public function __construct(ProcIngresosRepository $procIngresosRepository)
    {
        parent::__construct();
        $this->procIngresosRepository = $procIngresosRepository;
    }

    /**
     * @Route("/procIngresos", name="procIngresos")
     * @Route("/procIngresos/{id}", name="procIngresosid")
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
            if ($procIngresos = $this->procIngresosRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $procIngresos->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($procIngresoss = $this->procIngresosRepository->findAll()) {
            foreach ($procIngresoss as $procIngresos) {
                $data[] = $procIngresos->objectToArray();
            }
        } else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data, $message);
        return $this;
    }

    private function insertUpdate(){
        return $this;
    }
}