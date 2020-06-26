<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\File;

class ApiUploadedFile extends File
{
    private $ruta;
    private $realName;
    public function __construct($base64Content, $ruta)
    {
        $this->setRuta($ruta);
        $filePath = tempnam($this->getRuta(),'FotoFoodie');
        $file = fopen($filePath, "w");
        stream_filter_append($file, 'convert.base64-decode');

        fwrite($file, $base64Content);
        $meta_data = stream_get_meta_data($file);
        $path = $meta_data['uri'];
        fclose($file);

        parent::__construct($path, true);
        $this->setRealName();

        return $file;
    }

    private function setRuta($ruta){
        $this->ruta=__DIR__."/../../public{$ruta}";
        return $this;
    }

    private function getRuta(){
        return $this->ruta;
    }

    public function setRealName(){
        $this->realName="{$this->getPathname()}.{$this->guessExtension()}";
        return $this;
    }
    public function getRealname()
    {
        return $this->realName;
    }

    public function getFileName(){
        $list=explode('/',$this->getRealname());
        return ($list[count($list)-1]);
    }

    public function moveImage(){
        rename($this->getPathname(), $this->getRealname());
    }




}