<?php

namespace App\Entity;

use App\Repository\DonacionUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonacionUserRepository::class)
 */
class DonacionUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="donacionUser")
     * @ORM\JoinColumn(name="user_id",nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Donacion", inversedBy="donacionUser")
     * @ORM\JoinColumn(name="donacion_id", nullable=false)
     */
    private $donacion;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Donacion|null
     */
    public function getDonacion()
    {
        return $this->donacion;
    }

    /**

     * @return $this
     */
    public function setDonacion($donacion): self
    {
        $this->donacion = $donacion;

        return $this;
    }
}
