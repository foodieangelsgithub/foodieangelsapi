<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RelacionRepository")
 */
class Relacion
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
     * @ORM\OneToMany(targetEntity="App\Entity\Integrantes", mappedBy="sitLaboral", orphanRemoval=true)
     */
    private $integrantes;


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


    public function getIntegrantes(){
        return $this->integrantes;
    }

    /**
     * @param Integrantes $integrantes
     * @return $this
     */
    public function setIntegrantes(Integrantes $integrantes): self
    {
        $this->integrantes=$integrantes;
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
