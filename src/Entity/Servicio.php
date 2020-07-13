<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioRepository")
 */
class Servicio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Donacion", inversedBy="servicio")
     * @ORM\JoinColumn(name="donacion_id",nullable=false)
     */
    private $donacion;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiario", inversedBy="servicios")
     * @ORM\JoinColumn(name="beneficiario_id", nullable=true)
     */
    private $beneficiario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Voluntario", inversedBy="servicio")
     * @ORM\JoinColumn(name="voluntario_id",nullable=true)
     */
    private $voluntario_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fechaModi",type="datetime", nullable=true)
     */
    private $fechaModi;

    /**
     * @ORM\Column(type="smallint")
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="rutaFoto", type="string", length=200, nullable=true)
     */
    private $rutaFoto;

    /**
     * Servicio constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Donacion
     */
    public function getDonacion(): Donacion
    {
        return $this->donacion;
    }

    /**
     * @param Donacion|null $donacion
     * @return Servicio
     */
    public function setDonacion(?Donacion $donacion): self
    {
        $this->donacion = $donacion;

        return $this;
    }

    public function getBeneficiario(): ?Beneficiario
    {
        return $this->beneficiario;
    }

    public function setBeneficiario(?Beneficiario $beneficiario): self
    {
        $this->beneficiario = $beneficiario;

        return $this;
    }

    /**
     * @return Voluntario|null
     */
    
    public function getVoluntario(): ?Voluntario
    {
        return $this->voluntario_id;
    }


    public function setVoluntario($voluntarioId): self
    {
        $this->voluntario_id = $voluntarioId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     * @return Servicio
     */
    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    /**
     * @param \DateTimeInterface $fecha
     * @return Servicio
     */
    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFechaModi(): ?\DateTimeInterface
    {
        return $this->fechaModi;
    }

    /**
     * @param \DateTimeInterface|null $fechaModi
     * @return Servicio
     */
    public function setFechaModi(?\DateTimeInterface $fechaModi): self
    {
        $this->fechaModi = $fechaModi;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEstado(): ?int
    {
        return $this->estado;
    }

    /**
     * @param int $estado
     * @return Servicio
     */
    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRutaFoto(): ?string
    {
        return $this->rutaFoto;
    }

    /**
     * @param string|null $rutaFoto
     * @return Servicio
     */
    public function setRutaFoto(?string $rutaFoto): self
    {
        $this->rutaFoto = $rutaFoto;
        return $this;
    }




    /**
     * @return array
     */
    public function objectToArray()
    {


        $voluntario=($this->getVoluntario())?$this->getVoluntario():new Voluntario();
        $beneficiario=($this->getBeneficiario())?$this->getBeneficiario():new Beneficiario();
        $data=[
            'producto'                  => $this->getDonacion()->getProductId()->getId(),
            'cantidad'                  => $this->getCantidad(),
            'servicioId'                => $this->getId(),
            'donacionId'                => $this->getDonacion()->getId(),
            'cantidadtotal'             => $this->getDonacion()->getCantidad(),

            'proveedorId'               => $this->getDonacion()->getProveedorId()->getUserid()->getId(),
            'proveedorNombre'           => $this->getDonacion()->getProveedorId()->getNombre(),
            'proveedorApellidos'        => $this->getDonacion()->getProveedorId()->getApellidos(),
            'proveedorNombreUsuario'    => $this->getDonacion()->getProveedorId()->getUserid()->getUsername(),
            'proveedorTelefono'         => $this->getDonacion()->getProveedorId()->getUserid()->getTelephone(),
            'proveedorEmail'            => $this->getDonacion()->getProveedorId()->getUserid()->getEmail(),
            'proveedorProvincia'        => $this->getDonacion()->getProveedorId()->getProvincia()->getId(),
            'proveedorMunicipio'        => $this->getDonacion()->getProveedorId()->getMunicipio()->getId(),
            'proveedorProvinciaNombre'  => $this->getDonacion()->getProveedorId()->getProvincia()->getNombre(),
            'proveedorMunicipioNombre'  => $this->getDonacion()->getProveedorId()->getMunicipio()->getNombre(),
            'proveedorCPostal'          => $this->getDonacion()->getProveedorId()->getCodPostal(),


            'beneficiarioId'            => ($beneficiario->getUserid())?$beneficiario->getUserid()->getId():null,
            'beneficiarioNombre'        => ($beneficiario->getId())?$beneficiario->getNombre():null,
            'beneficiarioApellidos'     => ($beneficiario->getId())?$beneficiario->getApellidos():null,
            'beneficiarioNombreUsuario' => ($beneficiario->getId())?$beneficiario->getUserid()->getUsername():null,
            'beneficiarioProvincia'     => ($beneficiario->getId())?$beneficiario->getProvincia()->getId():null,
            'beneficiarioMunicipio'     => ($beneficiario->getId())?$beneficiario->getMunicipio()->getId():null,
            'beneficiarioProvinciaNombre' => ($beneficiario->getId())?$beneficiario->getProvincia()->getNombre():null,
            'beneficiarioMunicipioNombre' => ($beneficiario->getId())?$beneficiario->getMunicipio()->getNombre():null,
            'beneficiarioCPostal'      => ($beneficiario->getId())?$beneficiario->getCodPostal():null,
            'beneficiarioTelefono'     => ($beneficiario->getId())?$beneficiario->getUserid()->getTelephone():null,
            'beneficiarioEmail'        => ($beneficiario->getId())?$beneficiario->getUserid()->getEmail():null,
            'beneficiarioDireccion'    => ($beneficiario->getId())?$beneficiario->getDireccion():null,


            'voluntarioId'              => ($voluntario->getUserid())?$voluntario->getUserid()->getId():null,
            'voluntarioNombre'          => ($voluntario->getUserid())?$voluntario->getNombre():null,
            'voluntarioApellidos'       => ($voluntario->getUserid())?$voluntario->getApellidos():null,
            'voluntarioNombreUsuario'   => ($voluntario->getUserid())?$voluntario->getUserid()->getUsername():null,




            'rutaFoto'                  => $this->getRutaFoto(),
            //'fecha'                     => ($this->getFecha()!=NULL)?$this->getFecha()->format('Y-m-d h:i:s'):null,
            'fecha'                 => ($this->getFechaModi()!=NULL)?$this->getFechaModi()->format('Y-m-d h:i:s'):null,
            'estado'                    => $this->getEstado(),
            'direccion'                 => $this->getDonacion()->getProveedorId()->getDireccion()

        ];

        return $data;
        // TODO: Implement objectToArray() method.
    }


}
