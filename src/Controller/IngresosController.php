<?php

namespace App\Controller;

use App\Repository\IngresosRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IngresosController extends BaseController
{

    private $ingresoRepository;


    public function __construct(IngresosRepository $ingresoRepository)
    {
        parent::__construct();
        $this->ingresoRepository = $ingresoRepository;
    }

    /**
     * @Route("/ingresos", name="ingresos")
     * @Route("/ingresos/{id}", name="ingresosid")
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
            if ($ingreso = $this->ingresoRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $ingreso->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($ingresos = $this->ingresoRepository->findAll()) {
            foreach ($ingresos as $ingreso) {
                $data[] = $ingreso->objectToArray();
            }
        } else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data, $message);
        return $this;
    }

    private function insertUpdate()
    {
        return $this;
    }
}
