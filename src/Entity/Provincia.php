<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProvinciaRepository")
 */
class Provincia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre_alternativo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre_alternativo_bis;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $comunidad_id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $prefijo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zona;

    /**
     * @ORM\Column(type="string", length=450, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $temperatura;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais", inversedBy="provincias")
     */
    private $pais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Beneficiario", mappedBy="provincia")
     */
    private $beneficiarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Municipio", mappedBy="provincia_id")
     */
    private $municipios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proveedor", mappedBy="provincia")
     */
    private $proveedor;

    public function __construct()
    {
        $this->beneficiarios = new ArrayCollection();
        $this->municipios = new ArrayCollection();
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    public function getComunidadId(): ?int
    {
        return $this->comunidad_id;
    }

    public function setComunidadId(?int $comunidad_id): self
    {
        $this->comunidad_id = $comunidad_id;

        return $this;
    }

    public function getPrefijo(): ?string
    {
        return $this->prefijo;
    }

    public function setPrefijo(string $prefijo): self
    {
        $this->prefijo = $prefijo;

        return $this;
    }

    public function getZona(): ?string
    {
        return $this->zona;
    }

    public function setZona(string $zona): self
    {
        $this->zona = $zona;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTemperatura(): ?float
    {
        return $this->temperatura;
    }

    public function setTemperatura(?float $temperatura): self
    {
        $this->temperatura = $temperatura;

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

    public function getPaisId(): ?Pais
    {
        return $this->pais;
    }

    public function setPaisId(?Pais $pais): self
    {
        $this->pais = $pais;

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
            $beneficiario->setProvincia($this);
        }

        return $this;
    }

    public function removeBeneficiario(Beneficiario $beneficiario): self
    {
        if ($this->beneficiarios->contains($beneficiario)) {
            $this->beneficiarios->removeElement($beneficiario);
            // set the owning side to null (unless already changed)
            if ($beneficiario->getProvincia() === $this) {
                $beneficiario->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Municipio[]
     */
    public function getMunicipios(): Collection
    {
        return $this->municipios;
    }

    public function addMunicipio(Municipio $municipio): self
    {
        if (!$this->municipios->contains($municipio)) {
            $this->municipios[] = $municipio;
            $municipio->setProvinciaId($this);
        }

        return $this;
    }

    public function removeMunicipio(Municipio $municipio): self
    {
        if ($this->municipios->contains($municipio)) {
            $this->municipios->removeElement($municipio);
            // set the owning side to null (unless already changed)
            if ($municipio->getProvinciaId() === $this) {
                $municipio->setProvinciaId(null);
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
        if ($proveedor->getProvincia() !== $this) {
            $proveedor->setProvincia($this);
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