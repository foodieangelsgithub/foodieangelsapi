<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 */
class Producto
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
     * @ORM\OneToMany(targetEntity="App\Entity\Donacion", mappedBy="product_id")
     */
    private $donaciones;

    public function __construct()
    {
        $this->donaciones = new ArrayCollection();
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

    public function objectToArray()
    {
        $data=[
            'id'=>$this->getId(),
            'nombre'=>$this->getNombre()
        ];

        return $data;
        // TODO: Implement objectToArray() method.
    }

    /**
     * @return Collection|Donacion[]
     */
    public function getDonaciones(): Collection
    {
        return $this->donaciones;
    }

    public function addDonacione(Donacion $donacione): self
    {
        if (!$this->donaciones->contains($donacione)) {
            $this->donaciones[] = $donacione;
            $donacione->setProductId($this);
        }

        return $this;
    }

    public function removeDonacione(Donacion $donacione): self
    {
        if ($this->donaciones->contains($donacione)) {
            $this->donaciones->removeElement($donacione);
            // set the owning side to null (unless already changed)
            if ($donacione->getProductId() === $this) {
                $donacione->setProductId(null);
            }
        }

        return $this;
    }
}
