<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\HorariosRepository")
 * @ORM\Table(name="horarios")
 */

class Horarios
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiario", inversedBy="integrantes")
     * @ORM\JoinColumn(name="beneficiarioId",nullable=false)
     */

    /**
     * @ORM\ManyToOne(targetEntity="Proveedor", inversedBy="horarios")
     * @ORM\JoinColumn(name="proveedorid")
     */
    private $proveedorid;


    /**
     * @ORM\Column(name="dia", type="integer", nullable=false)
     */
    private $dia;

    /**
     *
     * @ORM\Column(name="abre", type="string", nullable=true)
     */
    private $abre;

    /**
     *
     * @ORM\Column(name="cierra", type="string", nullable=true)
     */
    private $cierra;

    /**
     * @ORM\Column(name="abierto", type="integer")
     */
    private $abierto;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Proveedor|null
     */
    public function getProveedorId(): ?Proveedor
    {
        return $this->proveedorid;
    }

    /**
     * @param Proveedor|null $proveedorid
     * @return $this
     */
    public function setProveedorId(?Proveedor $proveedorid): self
    {
        $this->proveedorid = $proveedorid;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDia(): ?int
    {
        return $this->dia;
    }

    /**
     * @param int $dia
     * @return $this
     */
    public function setDia(int $dia): self
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAbre(): ?string
    {
        return $this->abre;
    }

    /**
     * @param string|null $abre
     * @return $this
     */
    public function setAbre(string $abre): self
    {
        $this->abre = $abre;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCierra(): ?string
    {
        return $this->cierra;
    }

    /**
     * @param string|null $cierra
     * @return $this
     */
    public function setCierra(string $cierra): self
    {
        $this->cierra = $cierra;

        return $this;
    }

    public function getAbierto(): ?bool
    {
        return $this->abierto;
    }

    public function setAbierto(bool $abierto): self
    {
        $this->abierto = $abierto;

        return $this;
    }

    /**
     * @return array 'dia','abre','cierra','abierto'
     */
    public function objectToArray()
    {

        $data=[
            'dia'           => $this->getDia(),
            'abierto'       => $this->getAbierto()==1,
            'rangoHoras'    =>array(0=>array(
                'abre'          =>$this->getAbre(),
                'cierra'        =>$this->getCierra()
            ))
        ];
        // TODO: Implement objectToArray() method.
        return $data;
    }

}
