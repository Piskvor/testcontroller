<?php

namespace vendor\db;

interface IMySQLDriver
{
    /**
     * @param string $id
     * @return array
     */
    public function findProduct($id);
}
