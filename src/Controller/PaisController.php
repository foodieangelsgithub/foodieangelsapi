<?php

namespace App\Controller;




use App\Repository\PaisRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaisController extends BaseController
{


    public function __construct(PaisRepository $paisRepository)
    {
        parent::__construct();
        $this->paisRepository = $paisRepository;
    }

    /**
     * @Route("/pais", name="pais")
     * @Route("/pais/{id}", name="paisid")
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
            if ($pais = $this->paisRepository->findOneBy(array('id' => $this->getrequest()->get('id')))) {
                $data = $pais->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($paiss = $this->paisRepository->findAll()) {
            foreach ($paiss as $pais) {
                $data[] = $pais->objectToArray();
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