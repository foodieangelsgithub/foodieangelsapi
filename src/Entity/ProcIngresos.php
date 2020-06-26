<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProcIngresosRepository")
 * @ORM\Table(name="proc_ingresos")
 */
class ProcIngresos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Beneficiario", mappedBy="procIngresos")
     */
    private $beneficiarios;

    public function __construct()
    {
        $this->beneficiarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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



    /**
     * @return Collection|Beneficiario[]
     */
    public function getBeneficiarios(): Collection
    {
        return $this->beneficiarios;
    }

    public function addBeneficiario(Beneficiario $beneficiario): self
    {
        if (!$this->beneficiarios->contains($beneficiario)) {
            $this->beneficiarios[] = $beneficiario;
            $beneficiario->setMunicipio($this);
        }

        return $this;
    }

    public function removeBeneficiario(Beneficiario $beneficiario): self
    {
        if ($this->beneficiarios->contains($beneficiario)) {
            $this->beneficiarios->removeElement($beneficiario);
            // set the owning side to null (unless already changed)
            if ($beneficiario->getMunicipio() === $this) {
                $beneficiario->setMunicipio(null);
            }
        }

        return $this;
    }



    public function objectToArray()
    {
        $data=[
            'id'=>$this->getId(),
            'nombre'=>$this->getNombre()
        ];

        return $data;
        // TODO: Implement objectToArray() method.
    }
}