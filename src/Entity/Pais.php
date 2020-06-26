<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaisRepository")
 */
class Pais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Provincia", mappedBy="pais")
     */
    private $provincias;

    public function __construct()
    {
        $this->provincias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(?string $descr): self
    {
        $this->descr = $descr;

        return $this;
    }

    public function getPrefijo(): ?string
    {
        return $this->prefijo;
    }

    public function setPrefijo(?string $prefijo): self
    {
        $this->prefijo = $prefijo;

        return $this;
    }

    /**
     * @return Collection|Provincia[]
     */
    public function getProvincias(): Collection
    {
        return $this->provincias;
    }

    public function addProvincia(Provincia $provincia): self
    {
        if (!$this->provincias->contains($provincia)) {
            $this->provincias[] = $provincia;
            $provincia->setPaisId($this);
        }

        return $this;
    }

    public function removeProvincia(Provincia $provincia): self
    {
        if ($this->provincias->contains($provincia)) {
            $this->provincias->removeElement($provincia);
            // set the owning side to null (unless already changed)
            if ($provincia->getPaisId() === $this) {
                $provincia->setPaisId(null);
            }
        }

        return $this;
    }

    public function objectToArray()
    {
        $data=[
            'id'        => $this->getId(),
            'codigo'    => $this->getCodigo(),
            'descr'     => $this->getDescr(),
            'prefijo'   =>$this->getPrefijo()
        ];

        return $data;
        // TODO: Implement objectToArray() method.
    }
}
