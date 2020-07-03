<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProveedorRepository")
 */
class Proveedor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="proveedor")
     * @ORM\JoinColumn(name="userid", nullable=false)
     */
    private $userid;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="apellidos", type="string", length=100)
     */
    private $apellidos;

    /**
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provincia", inversedBy="proveedor")
     * @ORM\JoinColumn(name="provincia", nullable=false)
     */
    private $provincia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio", inversedBy="proveedor")
     * @ORM\JoinColumn(name="municipio", nullable=false)
     */
    private $municipio;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="codPostal", type="string", length=6)
     */
    private $codPostal;

    /**
     * @ORM\Column(name="redesSociales", type="boolean", nullable=true)
     */
    private $redesSociales;

    /**
     * @ORM\Column(name="lopd", type="boolean")
     */
    private $lopd;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fechaModi", type="datetime", nullable=true)
     */
    private $fechaModi;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Donacion", mappedBy="proveedor_id")
     */
    private $donaciones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horarios", mappedBy="proveedorid", orphanRemoval=true)
     */
    private $horarios;


    public function __construct()
    {
        $this->horarios = new ArrayCollection();
        $this->donaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(Provincia $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCodPostal(): ?string
    {
        return $this->codPostal;
    }

    public function setCodPostal(string $codPostal): self
    {
        $this->codPostal = $codPostal;

        return $this;
    }

    public function getRedesSociales(): ?bool
    {
        return $this->redesSociales;
    }

    public function setRedesSociales(?bool $redesSociales): self
    {
        $this->redesSociales = $redesSociales;

        return $this;
    }

    public function getLopd(): ?bool
    {
        return $this->lopd;
    }

    public function setLopd(bool $lopd): self
    {
        $this->lopd = $lopd;

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


    public function getHorarioToArray(){
        $i=array();
        $horarios=$this->getHorarios()->getIterator();
        /**
         * @var $horario Horarios
         */
        $x=-1;
        $diaactual=0;
        foreach($horarios as $horario){

            $horaData=$horario->objectToArray();
            if($diaactual!=$horaData['dia'] || $diaactual==0){
                $x++;
                $i[$x]=$horaData;
            }else{
                array_push($i[$x]['rangoHoras'],$horaData['rangoHoras'][0]);
            }
            $diaactual=$horaData['dia'];
        }
        return $i;
    }


    public function objectToArray()  {



        /**
         * "horario": [
            {
                "dia": 1,
                "abierto": true,
                "rangoHoras": [
                {
                    "abre": "8:00",
                    "cierra": "14:00"
                },
                {
                    "abre": "15:00",
                    "cierra": "20:00"
                }
            ]
        },
         */
        $data=[
            'id'                => $this->getUserid()->getId(),
            'active'            => $this->getUserid()->getActive(),
            'nombreUsuario'     => $this->getUserid()->getUsername(),
            'email'             => $this->getUserid()->getEmail(),
            'contrasenia'       => null,
            'nombre'            => $this->getNombre(),
            'apellidos'         => $this->getApellidos(),
            'telefono'          => $this->getTelefono(),
            'lopd'              => $this->getLopd(),
            'fecha'             => ($this->getFecha()!=NULL)?$this->getFecha()->format('Y-m-d h:i:s'):null,
            'fechaModi'         => ($this->getFechaModi()!=NULL)?$this->getFechaModi()->format('Y-m-d h:i:s'):null,
            'provincia'         => ($this->getProvincia()!=NULL)?$this->getProvincia()->getId():null,
            'municipio'         => ($this->getMunicipio()!=NULL)?$this->getMunicipio()->getId():null,
            'direccion'         => $this->getDireccion(),
            'codPostal'         => $this->getCodPostal(),
            'redesSociales'     => $this->getRedesSociales()==1,
            'horario'           => $this->getHorarioToArray()
        ];


        return $data;
    }

    /**
     * @return Collection|Donacion[]
     */
    public function getDonaciones(): Collection
    {
        return $this->donaciones;
    }

    public function addDonacione(Donacion $donacione): self
    {
        if (!$this->donaciones->contains($donacione)) {
            $this->donaciones[] = $donacione;
            $donacione->setProveedorId($this);
        }

        return $this;
    }

    public function removeDonacione(Donacion $donacione): self
    {
        if ($this->donaciones->contains($donacione)) {
            $this->donaciones->removeElement($donacione);
            // set the owning side to null (unless already changed)
            if ($donacione->getProveedorId() === $this) {
                $donacione->setProveedorId(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|Horarios[]
     */
    public function getHorarios(): Collection
    {
        return $this->horarios;
    }

    /**
     * @param array $horarios
     */
    public function setHorarios(array $horarios){
        foreach ($horarios as $horario){
            $this->addHorarios($horario);
        }
    }

    /**
     * @param Horarios $horario
     * @return $this
     */
    public function addHorarios(Horarios $horario): self
    {
        if (!$this->horarios->contains($horario)) {
            $this->horarios[] = $horario;
            $horario->setProveedorId($this);
        }

        return $this;
    }

    /**
     * @param Horarios $horario
     * @return $this
     */
    public function removeHorario(Horarios $horario): self
    {
        if ($this->horarios->contains($horario)) {
            $this->horarios->removeElement($horario);
            // set the owning side to null (unless already changed)
            if ($horario->getProveedorId() === $this) {
                $horario->setProveedorId(null);
            }
        }

        return $this;
    }
}
