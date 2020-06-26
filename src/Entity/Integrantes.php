<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntegrantesRepository")
 */
class Integrantes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="apellidos", type="string", length=100)
     */
    private $apellidos;

    /**
     * @ORM\Column(name="telefono",type="string", length=14, nullable=true)
     */
    private $telefono;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SituacionLaboral", inversedBy="integrantes")
     * @ORM\JoinColumn(name="sitLaboral", nullable=true)
     */
    private $sitLaboral;

    /**
     * @ORM\Column(name="discapacidad", type="integer")
     */
    private $discapacidad;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Relacion", inversedBy="integrantes")
     * @ORM\JoinColumn(name="relacion", nullable=true)
     */
    private $relacion;

    /**
     * @ORM\Column(name="fnacim", type="date", nullable=true)
     */
    private $fnacim;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiario", inversedBy="integrantes")
     * @ORM\JoinColumn(name="beneficiarioId",nullable=false)
     */
    private $beneficiario;

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
     * @return Integrantes
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
     * @return Integrantes
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
     * @return Integrantes
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
     * @return int|null
     */
    public function getDiscapacidad(): ?int
    {
        return $this->discapacidad;
    }

    /**
     * @param int $discapacidad
     * @return $this
     */
    public function setDiscapacidad(int $discapacidad): self
    {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    /**
     * @return Relacion|null
     */
    public function getRelacion(): ?Relacion
    {
        return $this->relacion;
    }

    /**
     * @param Relacion $relacion
     * @return $this
     */
    public function setRelacion(Relacion $relacion): self
    {
        $this->relacion = $relacion;

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
     * @return Beneficiario|null
     */
    public function getBeneficiario(): ?Beneficiario
    {
        return $this->beneficiario;
    }

    /**
     * @param Beneficiario|null $beneficiario
     * @return $this
     */
    public function setBeneficiario(?Beneficiario $beneficiario): self
    {
        $this->beneficiario = $beneficiario;

        return $this;
    }

    /**
     * @return array
     */
    public function objectToArray(){
        $data=array(
            'id'    =>          $this->getId(),
            'beneficiarioId'=>  $this->getBeneficiario()->getId(),
            'nombre'    =>      $this->getNombre(),
            'apellidos' =>      $this->getApellidos(),
            'telefono'  =>      $this->getTelefono(),
            'sitLaboral'=>      $this->getSitLaboral()->getId(),
            'discapacidad'=>    $this->getDiscapacidad(),
            'relacion'  =>      $this->getRelacion()->getId(),
            'fnacim'    =>      $this->getFnacim()->format('Y-m-d'));

        return $data;
    }
}
