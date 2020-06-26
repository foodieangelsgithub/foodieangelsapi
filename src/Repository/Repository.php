<?php

namespace App\Repository;

interface Repository
{

    /**
     * @param $table
     * @return mixed
     */
    public function setTable($table);

    /**
     * @return mixed
     */
    public function getTable();

    /**
     * @param $data
     * @return mixed
     */
    public function insertUpdateData($data);
}
