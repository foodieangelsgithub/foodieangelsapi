<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoluntarioRepository")
 */
class Voluntario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="voluntarios")
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
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="ambitoRecogida", type="json")
     */
    private $ambitoRecogida = [];

    /**
     * @ORM\Column(name="ambitoEntrega", type="json")
     */
    private $ambitoEntrega = [];

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
     * @ORM\OneToMany(targetEntity="App\Entity\Servicio", mappedBy="voluntario", orphanRemoval=false)
     */
    private $servicio;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Voluntario
     */
    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
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
     * @return Voluntario
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
     * @param string $apellidos
     * @return Voluntario
     */
    public function setApellidos(string $apellidos): self
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
     * @return Voluntario
     */
    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAmbitoRecogida(): ?array
    {
        return $this->ambitoRecogida;
    }

    /**
     * @param array $ambitoRecogida
     * @return Voluntario
     */
    public function setAmbitoRecogida(array $ambitoRecogida): self
    {
        $this->ambitoRecogida = $ambitoRecogida;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAmbitoEntrega(): ?array
    {
        return $this->ambitoEntrega;
    }

    /**
     * @param array $ambitoEntrega
     * @return Voluntario
     */
    public function setAmbitoEntrega(array $ambitoEntrega): self
    {
        $this->ambitoEntrega = $ambitoEntrega;

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
     * @param bool $lopd
     * @return Voluntario
     */
    public function setLopd(bool $lopd): self
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
     * @return Voluntario
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
     * @return Voluntario
     */
    public function setFechaModi(?\DateTimeInterface $fechaModi): self
    {
        $this->fechaModi = $fechaModi;

        return $this;
    }

    /**
     * @return array
     */
    public function objectToArray()  {



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
            'ambitoEntrega' => $this->getAmbitoEntrega(),
            'ambitoRecogida'=> $this->getAmbitoRecogida()
        ];



        return $data;
    }

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): self
    {
        $this->servicio = $servicio;

        return $this;
    }


    public function objectToArrayExtra(){
        $data = $this->objectToArray();

        $data["idPushBots"] = $this->getUserid()->getIdPushBots();
        $data["tokenPushBots"] = $this->getUserid()->getTokenPushBots();
        $data["oneSignalPlayerId"] = $this->getUserid()->getOneSignalPlayerId();

        return $data;
    }
}
