<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poblacion
 *
 * @ORM\Table(name="poblacion", indexes={@ORM\Index(name="IDX_7C27B8AA58BC1BE0", columns={"municipio_id"}), @ORM\Index(name="nombre", columns={"nombre"}), @ORM\Index(name="nombrealtbis", columns={"nombre_alternativo_bis"}), @ORM\Index(name="IDX_7C27B8AA4E7121AF", columns={"provincia_id"}), @ORM\Index(name="gid", columns={"gid"}), @ORM\Index(name="nombrealt", columns={"nombre_alternativo"})})
 * @ORM\Entity
 */
class Poblacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="municipio_id", type="integer", nullable=true)
     */
    private $municipioId;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=10, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre_alternativo", type="string", length=255, nullable=true)
     */
    private $nombreAlternativo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre_alternativo_bis", type="string", length=255, nullable=true)
     */
    private $nombreAlternativoBis;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */
    private $descripcion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="gid", type="string", length=50, nullable=true)
     */
    private $gid;

    /**
     * @var \Provincia
     *
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="provincia_id", referencedColumnName="id")
     * })
     */
    private $provincia;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getMunicipioId(): ?int
    {
        return $this->municipioId;
    }

    /**
     * @param int|null $municipioId
     * @return $this
     */
    public function setMunicipioId(?int $municipioId): self
    {
        $this->municipioId = $municipioId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     * @return $this
     */
    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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
    public function getNombreAlternativo(): ?string
    {
        return $this->nombreAlternativo;
    }

    /**
     * @param string|null $nombreAlternativo
     * @return $this
     */
    public function setNombreAlternativo(?string $nombreAlternativo): self
    {
        $this->nombreAlternativo = $nombreAlternativo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNombreAlternativoBis(): ?string
    {
        return $this->nombreAlternativoBis;
    }

    /**
     * @param string|null $nombreAlternativoBis
     * @return $this
     */
    public function setNombreAlternativoBis(?string $nombreAlternativoBis): self
    {
        $this->nombreAlternativoBis = $nombreAlternativoBis;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     * @return $this
     */
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGid(): ?string
    {
        return $this->gid;
    }

    /**
     * @param string|null $gid
     * @return $this
     */
    public function setGid(?string $gid): self
    {
        $this->gid = $gid;

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


}
