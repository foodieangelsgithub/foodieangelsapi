<?php

namespace App\Controller;




use App\Repository\ProvinciaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProvinciaController extends BaseController
{

    public function __construct(ProvinciaRepository $provinciaRepository)
    {
        parent::__construct();
        $this->provinciaRepository = $provinciaRepository;
    }

    /**
     * @Route("/provincia", name="provincia")
     * @Route("/provincia/{id}", name="provinciaid")
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
            if ($provincia = $this->provinciaRepository->findOneBy(array('id' => $this->getrequest()->get('id')))) {
                $data = $provincia->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($provincias = $this->provinciaRepository->findAll()) {
            foreach ($provincias as $provincia) {
                if($provincia->getId()==2295){
                    $data[] = $provincia->objectToArray();
                }
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