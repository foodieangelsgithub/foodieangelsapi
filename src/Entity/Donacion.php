<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="donacion")
 *
 * @ORM\Entity(repositoryClass="App\Repository\DonacionRepository")
 *
 */
class Donacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="donaciones")
     * @ORM\JoinColumn(name="producto_id", nullable=false)
     */
    private $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Proveedor", inversedBy="donaciones")
     * @ORM\JoinColumn(name="proveedor_id", nullable=false)
     */
    private $proveedor_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fechaModi", type="datetime", nullable=true)
     * @ORM\JoinColumn(name="fechaModi",nullable=true)
     */
    private $fechaModi;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Servicio", mappedBy="donacion", orphanRemoval=true)
     */
    private $servicio;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DonacionUser", mappedBy="donacion", orphanRemoval=true)
     */
    private $donacionUser;

    public function __construct()
    {
        $this->servicio = new ArrayCollection();

        $this->donacionUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?Producto
    {
        return $this->product_id;
    }

    public function setProductId(?Producto $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getProveedorId(): ?Proveedor
    {
        return $this->proveedor_id;
    }

    public function setProveedorId(?Proveedor $proveedor_id): self
    {
        $this->proveedor_id = $proveedor_id;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getFechaModi(): ?\DateTimeInterface
    {
        return $this->fechaModi;
    }

    public function setFechaModi(?\DateTimeInterface $fechaModi): self
    {
        $this->fechaModi = $fechaModi;

        return $this;
    }

    public function objectToArray()
    {


        $i=$this->getProveedorId()->getHorarioToArray();


        $data = [
            'id' => $this->getId(),
            'productoId'                => $this->getProductId()->getId(),
            'productoNombre'            => $this->getProductId()->getNombre(),
            'proveedorId'               => $this->getProveedorId()->getId(),
            'proveedorNombre'           => $this->getProveedorId()->getNombre(),
            'proveedorApellidos'        => $this->getProveedorId()->getApellidos(),
            'proveedorNombreUsuario'    => $this->getProveedorId()->getUserid()->getUsername(),
            'proveedormunicipio'        => $this->getProveedorId()->getMunicipio()->getNombre(),
            'proveedorprovincia'        => $this->getProveedorId()->getProvincia()->getNombre(),
            'proveedorhorario'          => $i,
            'cantidad'                  => $this->getCantidad(),
            'disponible'                => $this->getTotal() /*cantidad disponible*/,
            'direccion'                 => $this->getProveedorId()->getDireccion(),
            'estado'                    => $this->getEstado()
        ];

        $i=array();
        $servicios=$this->getServicios()->getIterator();

        /**
         * @var $servicio Servicio
         */
        foreach($servicios as $servicio){
            $i[]=$servicio->objectToArray();
        }
        $data['servicios']=$i;



        return $data;
    }


    /**
     * @return Collection|Servicio[]
     */
    public function getServicios(): Collection
    {
        return $this->servicio;
    }

    public function setServicios(array $servicios){
        foreach ($servicios as $servicio){
            $this->addServicio($servicio);
        }
    }

    public function addServicio(Servicio $servicio): self
    {
        if (!$this->servicio->contains($servicio)) {
            $this->servicio[] = $servicio;
            $servicio->setDonacion($this);
        }

        return $this;
    }

    public function removeServicio(Servicio $servicio): self
    {
        if ($this->servicio->contains($servicio)) {
            $this->servicio->removeElement($servicio);
            // set the owning side to null (unless already changed)
            if ($servicio->getDonacion() === $this) {
                $servicio->setDonacion(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     * @return Donacion
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }


    public function getDonacionUser()
    {
        return $this->donacionUser;
    }

    public function setDonacionUser(?DonacionUser $donacionUser): self
    {
        $this->donacionUser = $donacionUser;

        return $this;
    }
}
