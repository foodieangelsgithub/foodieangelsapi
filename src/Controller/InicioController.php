<?php

namespace App\Controller;



use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends BaseController
{

    private $proveedorController;
    private $ingresosController;
    private $beneficiarioController;
    private $voluntarioController;

    public function __construct(ProveedorController $proveedorController, IngresosController $ingresosController, BeneficiarioController $beneficiarioController, VoluntarioController $voluntarioController)
    {
        parent::__construct();
        $this->proveedorController=$proveedorController;
        $this->beneficiarioController=$beneficiarioController;
        $this->ingresosController=$ingresosController;
        $this->voluntarioController=$voluntarioController;

    }

    /**
     * @Route("/inicio", name="inicio")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
    }
}
