<?php

namespace App\Entity;

use App\Repository\DonacionLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonacionLogRepository::class)
 */
class DonacionLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Donacion::class)
     * @ORM\JoinColumn(name="id_donacion", nullable=false)
     */
    private $id_donacion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $estado_donacion;

    /**
     * @ORM\ManyToOne(targetEntity=Servicio::class)
     * @ORM\JoinColumn(name="id_servicio", nullable=false)
     */
    private $id_servicio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $estado_servicio;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDonacion(): ?Donacion
    {
        return $this->id_donacion;
    }

    public function setIdDonacion(?Donacion $id_donacion): self
    {
        $this->id_donacion = $id_donacion;

        return $this;
    }

    public function getEstadoDonacion(): ?int
    {
        return $this->estado_donacion;
    }

    public function setEstadoDonacion(?int $estado_donacion): self
    {
        $this->estado_donacion = $estado_donacion;

        return $this;
    }

    public function getIdServicio(): ?Servicio
    {
        return $this->id_servicio;
    }

    public function setIdServicio(?Servicio $id_servicio): self
    {
        $this->id_servicio = $id_servicio;

        return $this;
    }

    public function getEstadoServicio(): ?int
    {
        return $this->estado_servicio;
    }

    public function setEstadoServicio(?int $estado_servicio): self
    {
        $this->estado_servicio = $estado_servicio;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}
