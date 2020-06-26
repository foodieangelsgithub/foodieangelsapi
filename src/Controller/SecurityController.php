<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             $this->jsonSuccess(($this->getUser()->objectToArray()));
         }else{
             $this->jsonError('No está logado');
         }
        return $this->returnResponse();
    }

    /**
     * @Route("/userVerificar", name="user_login")
     */
    public function verificar(){



        return $this->render('security/login.html.twig', ['last_username' => '', 'error' => '']);

    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->jsonError('No está logado');

        return $this->returnResponse();
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
