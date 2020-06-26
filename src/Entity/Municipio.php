<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MunicipioRepository")
 */
class Municipio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provincia", inversedBy="municipios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_alternativo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre_alternativo_bis;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Beneficiario", mappedBy="municipio")
     */
    private $beneficiarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proveedor", mappedBy="provincia")
     */
    private $proveedor;

    public function __construct()
    {
        $this->beneficiarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvinciaId(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvinciaId(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombreAlternativo(): ?string
    {
        return $this->nombre_alternativo;
    }

    public function setNombreAlternativo(string $nombre_alternativo): self
    {
        $this->nombre_alternativo = $nombre_alternativo;

        return $this;
    }

    public function getNombreAlternativoBis(): ?string
    {
        return $this->nombre_alternativo_bis;
    }

    public function setNombreAlternativoBis(?string $nombre_alternativo_bis): self
    {
        $this->nombre_alternativo_bis = $nombre_alternativo_bis;

        return $this;
    }

    public function getGid(): ?string
    {
        return $this->gid;
    }

    public function setGid(?string $gid): self
    {
        $this->gid = $gid;

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

    public function getProveedor(): ?Proveedor
    {
        return $this->proveedor;
    }

    public function setProveedor(Proveedor $proveedor): self
    {
        $this->proveedor = $proveedor;

        // set the owning side of the relation if necessary
        if ($proveedor->getMunicipio() !== $this) {
            $proveedor->setMunicipio($this);
        }

        return $this;
    }

    public function objectToArray()
    {
        $data=[
            'id'                => $this->getId(),
            'nombre'            => $this->getNombre(),
            'provincia'         => $this->getProvinciaId()->getId(),
            'provincianombre'   => $this->getProvinciaId()->getNombre()
        ];

        return $data;
        // TODO: Implement objectToArray() method.
    }
}
