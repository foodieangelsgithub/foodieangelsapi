<?php

namespace App\Controller;


use App\Repository\BeneficiarioRepository;
use App\Repository\LogsRepository;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends BaseController
{

    /**
     * @Route("/logs", name="logs")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){

        $this->setRequest($request);

        if(!$this->verifyUser()){
            $data='';
        }else {
            if ($this->getRequest()->getMethod() == 'GET') {
                $data = $this->getData();
            }elseif($this->getRequest()->getMethod() == 'POST') {
                $this->deleteLog();
                $data = $this->getData();
            }
        }

        return $this->render('log/log.html.twig', ['logs' => $data]);
    }

    private function getData(){
        $logsRepository = new LogsRepository($this->getConnection());

        return $logsRepository->setOrderBy('created_at desc, id asc')->getAll();
    }

    private function deleteLog(){

        if($this->getRequest()->get('id')) {
            $logsRepository = new LogsRepository($this->getConnection());
            return $logsRepository->deleteData(['id' => $this->getRequest()->get('id')]);
        }else{
            return false;
        }
    }

}
