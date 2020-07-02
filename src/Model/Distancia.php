<?php

namespace App\Model;


/**
 * Class Distancia
 * @package App\Model
 */
class Distancia{

    /**
     * @var integer
     */
    protected $distanciaMinV;

    /**
     * @var integer
     */
    protected $distanciaMaxV;

    /**
     * @var integer
     */
    protected $cantidadMaxV;

    /**
     * @var integer
     */
    protected $cantidadActualV=0;

    /**
     * @var integer
     */
    protected $distanciaMinB;

    /**
     * @var integer
     */
    protected $distanciaMaxB;

    /**
     * @var integer
     */
    protected $cantidadMaxB;

    /**
     * @var integer
     */
    protected $cantidadActualB=0;


    /**
     * Distancia máxima en kilómetros a la que buscar los voluntarios
     * @param $distance
     * @return $this
     */
    public function setDistanciaMaxV($distance){
        $this->distanciaMaxV = $distance;
        return $this;
    }

    /**
     * Distancia mínima en kilómetros a la que buscar los voluntarios
     * @param $distance
     * @return $this
     */
    public function setDistanciaMinV($distance){
        $this->distanciaMinV = $distance;
        return $this;
    }

    /**
     * Distancia máxima en kilómetros a la que buscar los beneficiarios
     * @param $distance
     * @return $this
     */
    public function setDistanciaMaxB($distance){
        $this->distanciaMaxB = $distance;
        return $this;
    }

    /**
     * Distancia mínima en kilómetros a la que buscar los beneficiarios
     * @param $distance
     * @return $this
     */
    public function setDistanciaMinB($distance){
        $this->distanciaMinB = $distance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistanciaMaxV(){
        return $this->distanciaMaxV;
    }

    /**
     * @return mixed
     */
    public function getDistanciaMinV(){
        return $this->distanciaMinV;
    }

    /**
     * @return mixed
     */
    public function getDistanciaMaxB(){
        return $this->distanciaMaxB;
    }

    /**
     * @return mixed
     */
    public function getDistanciaMinB(){
        return $this->distanciaMinB;
    }

    /**
     * Cantidad máxima de voluntarios a buscar
     * @param $cant
     * @return $this
     */
    public function setCantidadV($cant){
        $this->cantidadMaxV = $cant;
        return $this;
    }

    /**
     * Cantidad máxima de beneficiarios a buscar
     * @param $cant
     * @return $this
     */
    public function setCantidadB($cant){
        $this->cantidadMaxB = $cant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCantidadMaxV(){
        return $this->cantidadMaxV;
    }

    /**
     * @return mixed
     */
    public function getCantidadMaxB(){
        return $this->cantidadMaxB;
    }

    /**
     * @return mixed
     */
    public function setCantidadActualB($cant){
        $this->cantidadActualB = $cant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function setCantidadActualV($cant){
        $this->cantidadActualV = $cant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCantidadActualV(){
        return $this->cantidadActualV;
    }

    /**
     * @return mixed
     */
    public function getCantidadActualB(){
        return $this->cantidadActualB;
    }
}