<?php

namespace App\Helper;


use Doctrine\ORM\EntityManager;

trait repositoryTrait {

    private $entityManagerTransaction;
    private $message;

    /**
     * @param EntityManager $entityManagerTransaction
     * @return $this
     *
     */
    public function setEntityManagerTransaction($entityManagerTransaction){
        $this->entityManagerTransaction=$entityManagerTransaction;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManagerTransaction(){
        return $this->entityManagerTransaction;
    }

    /**
     * @param String $message
     * @return $this
     *
     */
    public function setMessage($message){
        $this->message=$message;
        return $this;
    }

    /**
     * @return String
     */
    public function getMessage(){
        return $this->message;
    }

}