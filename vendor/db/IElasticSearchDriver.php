<?php

namespace vendor\db;

interface IElasticSearchDriver
{
    /**
     * @param string $id
     * @return array
     */
    public function findById($id);
}
