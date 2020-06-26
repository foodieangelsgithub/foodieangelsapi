<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{


    public $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/user/disabled", name="userdisabled")
     * @Route("/user/disabled/{id}", name="userdisabledid")
     * @param Request $request
     * @return JsonResponse
     */
    public function disabled(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->setrequest($request);
        $data=array();
        $message=$this->getNoData();
        if ($this->getrequest() && $this->getrequest()->get('id') && $this->notEmptyOnrequest('id')) {
            if(is_numeric($this->getrequest()->get('id'))){
                $users = $this->userRepository->findOneBy(array('id' => $this->getrequest()->get('id')));
                $users->setActive(!$users->getActive());
                $this->userRepository->saveUser($users);
                $this->userRepository->getEntityManagerTransaction()->commit();
                $data[] = $users->objectToArray();
                $message='';
            }
        }
        $this->jsonSuccess($data,$message);
        return $this->returnResponse();
    }



    /**
     * @Route("/user", name="user")
     * @Route("/user/{id}", name="userid")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->setrequest($request);
        if ($this->getrequest()->getMethod() == 'GET') {
            return $this->getAll()->returnResponse();
        } else {
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

            if(is_numeric($this->getrequest()->get('id'))){
                $users = $this->userRepository->findOneBy(array('id' => $this->getrequest()->get('id')));
            }else{
                $users = $this->userRepository->findUserByRol($this->getrequest()->get('id'));
            }
            if (is_array($users)) {
                /**
                 * @var $user User
                 */
                foreach ($users as $user) {

                    if(($this->getUser()->getUsername()!='admin' && $user->getUsername()!='admin') || $this->getUser()->getUsername()=='admin'){
                        $user->setApiToken(null);
                        $data[] = $user->objectToArray();
                    }

                }
            }elseif($users){
                $data = $users->objectToArray();
            }else
                $message=$this->getNoData();
        } elseif ($users = $this->userRepository->findAll()) {
            foreach ($users as $user) {
                if(($this->getUser()->getUsername()!='admin' && $user->getUsername()!='admin') || $this->getUser()->getUsername()=='admin'){
                    $data[] = $user->objectToArray();
                }

            }
        } else {
            $message=$this->getNoData();
        }
        $this->jsonSuccess($data,$message);
        return $this;
    }

    /**
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function insertUpdate(){
        $data=array();
        $message='Usuario creado con éxito';
        if($this->notEmptyOnrequest('id') && $this->getrequest()->get('id')>0){
            $user=$this->userRepository->findOneBy(array('id'=>$this->getrequest()->get('id')));
            if($user){
                $userFindEmail=null;
                if(($this->notEmptyOnrequest('email') && $user->getEmail()!=$this->getrequest()->get('email'))){
                    $userFindEmail=$this->userRepository->findOneBy(array('email'=>$this->getrequest()->get('email')));
                }

                if($userFindEmail){
                    $this->jsonError('Ese email ya pertenece a otro usuario');
                }else{
                    if($this->notEmptyOnrequest(['contrasenia'])){
                        $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    }
                    $this->fillUser($user);
                    if(!$this->userRepository->saveUser($user)){
                        $this->jsonError($this->userRepository->getMessage());
                        $this->userRepository->getEntityManagerTransaction()->rollback();
                    }else {
                        $this->userRepository->getEntityManagerTransaction()->commit();
                        $data = $user->objectToArray();
                        $this->jsonSuccess($data,$message);
                    }
                }
            }else{
                $this->jsonError('Este usuario no existe no se puede actualizar');
            }
        }else{
            $user=$userFindEmail=null;
            if($this->notEmptyOnrequest('email')){
                $userFindEmail=$this->userRepository->findOneBy(array('email'=>$this->getrequest()->get('email')));
            }


            if($user || $userFindEmail){
                $this->jsonError('Ese email ya pertenece a otro usuario');
            }elseif(!$this->notEmptyOnrequest('email')){
                $this->jsonError('Email está vacío');
            }elseif($this->userRepository->findOneBy(array('username'=>$this->getrequest()->get('nombreUsuario')))){
                $this->jsonError('Este nombre de usuario existe');
            }else{
                if($this->notEmptyOnrequest(['nombreUsuario','contrasenia'])){
                    $this->getrequest()->attributes->set('roles', ['ROLE_ADMIN']);
                    $this->getrequest()->attributes->set('usernameIdUnique', $this->getrequest()->get('nombreUsuario'));
                    $this->getrequest()->attributes->set('passwordIdUnique', $this->getrequest()->get('contrasenia'));
                    $user = $this->fillUser(new User());

                    $user->setDatecreate(new \DateTime());
                    /**
                     * Activamos siempre al usuario creado
                     */
                    $this->getrequest()->attributes->set('active', 1);
                    if($this->userRepository->insertUser($user)){
                        $this->userRepository->getEntityManagerTransaction()->commit();
                        $data = $user->objectToArray();
                        $this->jsonSuccess($data,$message);
                    } else {
                        $this->userRepository->getEntityManagerTransaction()->rollback();
                        $this->jsonError('Ha habido un error al crear el usuario');
                    }
                }else{
                    $this->jsonError('El nombre de usuario y la contraseña son obligatorias');
                }
            }

        }
        return $this;
    }


    /**
     * @param User $user
     * @return User|bool
     * @throws \Exception
     */
    public function fillUser(User $user)
    {
        return parent::fillUser($user); // TODO: Change the autogenerated stub
    }
}
