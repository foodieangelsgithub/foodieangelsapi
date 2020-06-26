<?php

namespace App\Controller;

use App\Repository\SituacionLaboralRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SituacionLaboralController extends BaseController
{


    private $situacionlaboralRepository;


    public function __construct(SituacionLaboralRepository $situacionlaboralRepository)
    {
        parent::__construct();
        $this->situacionlaboralRepository = $situacionlaboralRepository;
    }

    /**
     * @Route("/situacionlaboral", name="situacionlaboral")
     * @Route("/situacionlaboral/{id}", name="situacionlaboralid")
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
            if ($situacionlaboral = $this->situacionlaboralRepository->findOneBy(array('userid' => $this->getrequest()->get('id')))) {
                $data = $situacionlaboral->objectToArray();
            } else
                $message=$this->getNoData();
        } elseif ($situacionlaborals = $this->situacionlaboralRepository->findAll()) {
            foreach ($situacionlaborals as $situacionlaboral) {
                $data[] = $situacionlaboral->objectToArray();
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
