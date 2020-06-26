<?php
namespace App\Interfase;

interface TransformEntityInterfase {

    public function toArray();

    public function transformDateToJson();

}