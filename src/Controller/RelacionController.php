<?php

namespace App\Controller;




use App\Repository\RelacionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RelacionController extends BaseController
{


    public function __construct(RelacionRepository $relacionRepository)
    {
        parent::__construct();
        $this->relacionRepository = $relacionRepository;
    }

    /**
     * @Route("/relacion", name="relacion")
     * @Route("/relacion/{id}", name="relacionid")
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
            if ($relacion = $this->relacionRepository->findOneBy(array('id' => $this->getrequest()->get('id')))) {
                $data = $relacion->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($relacions = $this->relacionRepository->findAll()) {
            foreach ($relacions as $relacion) {
                $data[] = $relacion->objectToArray();
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