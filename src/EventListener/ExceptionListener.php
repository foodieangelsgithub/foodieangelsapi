<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use App\Controller\BaseController;
use App\Entity\Logs;
use App\Repository\LogsRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\DriverException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ExceptionListener
{

    public $logsRepository;
    public $connection;
    public function __construct(Connection $connection, LogsRepository $logsRepository)
    {
        $this->connection=$connection;
        $this->logsRepository = $logsRepository;
    }

    public function onKernelController(ControllerEvent $event){

    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method  = $request->getRealMethod();
        if ('OPTIONS' == $method) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            $event->setResponse($response);
        }

        /*
          $json=['status'=>'errors','message'=> $event->getResponse()];


          $response = new Response();
          $response->setContent(json_encode($json, JSON_PRETTY_PRINT));
          $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

          $event->setResponse($response);
        */
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $method  = $request->getRealMethod();
        if ('OPTIONS' == $method) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            $event->setResponse($response);
        }


    }
    public function onKernelException(ExceptionEvent $event)
    {

        $exception = $event->getThrowable();

        if($exception instanceof AccessDeniedHttpException){
            $json=['status'=>'error','message'=> $exception->getMessage()];
        }elseif($exception instanceof BadCredentialsException){
            $json=['status'=>'error','message'=> 'usuario y contraseña incorrecta'];
        }
        elseif($exception instanceof NotFoundHttpException){
            $json=['status'=>'error','message'=> 'Sección no existe'];
        }else{

            if ($exception instanceof DriverException || $exception instanceof DBALException) {
                $json=['status'=>'error',
                    'exception'=> $exception->getMessage(),
                    'message'=>'Ha habido un error al intentar guardar los datos en la base de datos'];
            }else{
                $json=['status'=>'error','message'=> $exception->getMessage()];
            }
            $this->saveLog($exception);
        }

        $response = new Response();
        $response->setContent(json_encode($json, JSON_PRETTY_PRINT));
         if ($exception instanceof HttpExceptionInterface) {
             $response->setStatusCode($exception->getStatusCode());
             $response->headers->replace($exception->getHeaders());
         } else {
             $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
         }

        $event->setResponse($response);

/*

    // Customize your response object to display the exception details
            $response = new Response();
            $response->setContent($message);

    // HttpExceptionInterface is a special type of exception that
    // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

    // sends the modified response object to the event
        $event->setResponse($response);
    */
    }


    private function saveLog($exception){

        return true;
        $trace=$exception->getTrace();
        $padreid=null;

        foreach($trace as $key=>$d){

            $logs=new Logs();
            $logs->setDescription($exception->getMessage())
                ->setFile(array_key_exists('file',$d)?$d['file']:'')
                ->setLine(array_key_exists('line',$d)?$d['line']:'')
                ->setFunction(array_key_exists('function',$d)?$d['function']:'')
                ->setCreatedAt(new \DateTime())
                ->setPadreid($padreid);

            $this->connection->
            $this->logsRepository->insertLogs($logs);
            //$this->logsRepository->getEntityManagerTransaction()->commit();
            if($key==0){
                $padreid=$logs->getId();
            }
            if($key>5){
                break;
            }
        }
    }
}