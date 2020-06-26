<?php

namespace App\Controller;

use App\Repository\ProductoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductoController extends BaseController
{

    private $productoRepository;


    public function __construct(ProductoRepository $productoRepository)
    {
        parent::__construct();
        $this->productoRepository = $productoRepository;
    }

    /**
     * @Route("/producto", name="producto")
     * @Route("/producto/{id}", name="productoid")
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
            if ($producto = $this->productoRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $producto->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($productos = $this->productoRepository->findAll()) {
            foreach ($productos as $producto) {
                $data[] = $producto->objectToArray();
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
