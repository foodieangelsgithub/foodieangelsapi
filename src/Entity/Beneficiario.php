<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="beneficiario", indexes={@ORM\Index(name="FK_beneficiario_user", columns={"userid"})})
 *
 * @ORM\Entity(repositoryClass="App\Repository\BeneficiarioRepository")
 *
 */
class Beneficiario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="userid", nullable=false)
     */
    private $userid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provincia", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="provincia", nullable=false)
     */
    private $provincia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="municipio", nullable=false)
     */
    private $municipio;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="apellidos", type="string", length=100, nullable=true)
     */
    private $apellidos;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="discapacidad", type="integer", nullable=true)
     */
    private $discapacidad;

    /**
     * @ORM\Column(name="fnacim", type="datetime", nullable=true)
     */
    private $fnacim;

    /**
     * @ORM\Column(name="codPostal", type="string", length=6, nullable=true)
     */
    private $codPostal;

    /**
     * @ORM\Column(name="lopd", type="boolean", nullable=true)
     */
    private $lopd;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="direccion", type="text", nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="fechaModi", type="datetime", nullable=true)
     */
    private $fechaModi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SituacionLaboral", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="sitLaboral", nullable=false)
     */
    private $sitLaboral;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ingresos", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="ingresos", nullable=false)
     */
    private $ingresos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProcIngresos", inversedBy="beneficiarios")
     * @ORM\JoinColumn(name="procIngresos", nullable=false)
     */
    private $procIngresos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Integrantes", mappedBy="beneficiario", orphanRemoval=true)
     */
    private $integrantes;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Servicio", mappedBy="beneficiario", orphanRemoval=true)
     */
    private $servicios;


    /**
     * Beneficiario constructor.
     */
    public function __construct()
    {
        $this->integrantes = new ArrayCollection();
        $this->servicios = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return $this
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    /**
     * @param string|null $apellidos
     * @return $this
     */
    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    /**
     * @param string|null $telefono
     * @return $this
     */
    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return SituacionLaboral|null
     */
    public function getSitLaboral(): ?SituacionLaboral
    {
        return $this->sitLaboral;
    }

    /**
     * @param SituacionLaboral|null $sitLaboral
     * @return $this
     */
    public function setSitLaboral(?SituacionLaboral $sitLaboral): self
    {
        $this->sitLaboral = $sitLaboral;

        return $this;
    }

    /**
     * @return Municipio|null
     */
    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    /**
     * @param Municipio|null $municipio
     * @return $this
     */
    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDiscapacidad(): ?int
    {
        return $this->discapacidad;
    }

    /**
     * @param int|null $discapacidad
     * @return $this
     */
    public function setDiscapacidad(?int $discapacidad): self
    {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFnacim(): ?\DateTimeInterface
    {
        return $this->fnacim;
    }

    /**
     * @param \DateTimeInterface|null $fnacim
     * @return $this
     */
    public function setFnacim(?\DateTimeInterface $fnacim): self
    {
        $this->fnacim = $fnacim;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCodPostal(): ?string
    {
        return $this->codPostal;
    }

    /**
     * @param string|null $codPostal
     * @return $this
     */
    public function setCodPostal(?string $codPostal): self
    {
        $this->codPostal = $codPostal;

        return $this;
    }

    /**
     * @return Ingresos|null
     */
    public function getIngresos(): ?Ingresos
    {
        return $this->ingresos;
    }

    /**
     * @param Ingresos|null $ingresos
     * @return $this
     */
    public function setIngresos(?Ingresos $ingresos): self
    {
        $this->ingresos = $ingresos;

        return $this;
    }

    /**
     * @return ProcIngresos|null
     */
    public function getProcIngresos(): ?ProcIngresos
    {
        return $this->procIngresos;
    }

    /**
     * @param ProcIngresos|null $procIngresos
     * @return $this
     */
    public function setProcIngresos(?ProcIngresos $procIngresos): self
    {
        $this->procIngresos = $procIngresos;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getLopd(): ?bool
    {
        return $this->lopd;
    }

    /**
     * @param bool|null $lopd
     * @return $this
     */
    public function setLopd(?bool $lopd): self
    {
        $this->lopd = $lopd;

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
     * @return $this
     */
    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUserid(): ?User
    {
        if($this->userid!=null){
            return $this->userid;
        }else{
            return new User();
        }
    }

    /**
     * @param User|null $userid
     * @return $this
     */
    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    /**
     * @param string|null $direccion
     * @return $this
     */
    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Provincia|null
     */
    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    /**
     * @param Provincia|null $provincia
     * @return $this
     */
    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

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
     * @return $this
     */
    public function setFechaModi(?\DateTimeInterface $fechaModi): self
    {
        $this->fechaModi = $fechaModi;

        return $this;
    }

    /**
     * @return Collection|Integrantes[]
     */
    public function getIntegrantes(): Collection
    {
        return $this->integrantes;
    }

    /**
     * @param array $integrantes
     */
    public function setIntegrantes(array $integrantes){
        foreach ($integrantes as $integrante){
            $this->addIntegrante($integrante);
        }
    }

    /**
     * @param Integrantes $integrante
     * @return $this
     */
    public function addIntegrante(Integrantes $integrante): self
    {
        if (!$this->integrantes->contains($integrante)) {
            $this->integrantes[] = $integrante;
            $integrante->setBeneficiario($this);
        }

        return $this;
    }

    /**
     * @param Integrantes $integrante
     * @return $this
     */
    public function removeIntegrante(Integrantes $integrante): self
    {
        if ($this->integrantes->contains($integrante)) {
            $this->integrantes->removeElement($integrante);
            // set the owning side to null (unless already changed)
            if ($integrante->getBeneficiario() === $this) {
                $integrante->setBeneficiario(null);
            }
        }

        return $this;
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function objectToArray()  {

        $i=array();
        $integrantes=$this->getIntegrantes()->getIterator();
        /**
         * @var $integrante Integrantes
         */
        foreach($integrantes as $integrante){
            $i[]=$integrante->objectToArray();
        }

        $data=[
            'id'            => $this->getUserid()->getId(),
            'active'        => $this->getUserid()->getActive(),
            'nombreUsuario' => $this->getUserid()->getUsername(),
            'email'         => $this->getUserid()->getEmail(),
            'contrasenia'   => null,
            'nombre'        => $this->getNombre(),
            'apellidos'     => $this->getApellidos(),
            'telefono'      => $this->getTelefono(),
            'lopd'          => $this->getLopd(),
            'fecha'         => ($this->getFecha()!=NULL)?$this->getFecha()->format('Y-m-d h:i:s'):null,
            'fechaModi'     => ($this->getFechaModi()!=NULL)?$this->getFechaModi()->format('Y-m-d h:i:s'):null,
            'provincia'     => ($this->getProvincia()!=NULL)?$this->getProvincia()->getId():null,
            'municipio'     => ($this->getMunicipio()!=NULL)?$this->getMunicipio()->getId():null,
            'direccion'     => $this->getDireccion(),
            'codPostal'     => $this->getCodPostal(),
            'sitLaboral'    => $this->getSitLaboral()->getId(),
            'discapacidad'  => $this->getDiscapacidad(),
            'fnacim'        => ($this->getFnacim()!=NULL)?$this->getFnacim()->format('Y-m-d'):null,
            'ingresos'      => $this->getIngresos()->getId(),
            'procIngresos'  => $this->getProcIngresos()->getId(),
            'numIntegrantes'=> count($i),
            'integrantes'   => $i
        ];


        return $data;
    }


    public function objectToArrayExtra(){
        $data = $this->objectToArray();

        $data["idPushBots"] = $this->getUserid()->getIdPushBots();
        $data["tokenPushBots"] = $this->getUserid()->getTokenPushBots();
        $data["oneSignalPlayerId"] = $this->getUserid()->getOneSignalPlayerId();

        return $data;
    }

    /**
     * @return Collection|Servicio[]
     */
    public function getServicios(): Collection
    {
        return $this->servicios;
    }


    /**
     * @param Servicio $servicio
     * @return $this
     */
    public function addServicios(Servicio $servicio): self
    {
        if (!$this->servicios->contains($servicio)) {
            $this->servicios[] = $servicio;
            $servicio->setBeneficiario($this);
        }

        return $this;
    }

    /**
     * @param Servicio $servicio
     * @return $this
     */
    public function removeServicio(Servicio $servicio): self
    {
        if ($this->servicios->contains($servicio)) {
            $this->servicios->removeElement($servicio);
            // set the owning side to null (unless already changed)
            if ($servicio->getBeneficiario() === $this) {
                $servicio->setBeneficiario(null);
            }
        }

        return $this;
    }



}
