<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="codigopostal")
 * @ORM\Entity(repositoryClass="App\Repository\CodigoPostalRepository")
 */
class CodigoPostal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, unique=true)
     */
    protected $codigo;

    /**
     * @ORM\Column(type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $lat;

    /**
     * @ORM\Column(type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $lon;

    /**
     * @ORM\Column(type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $distance;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $long_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $short_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $formatted_address ;

    /**
     * @ORM\Column(name="northeastLon", type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $northeastLon;

    /**
     * @ORM\Column(name="northeastLat", type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $northeastLat;

    /**
     * @ORM\Column(name="southwestLon", type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $southwestLon;

    /**
     * @ORM\Column(name="southwestLat", type="decimal", precision=31, scale=14, nullable=true)
     */
    protected $southwestLat;

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

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(?string $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function objectToArray()
    {
        // TODO: Implement objectToArray() method.
    }

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDistance(){
        return $this->distance;
    }
}
