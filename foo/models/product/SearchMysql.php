<?php

namespace foo\models\Product;

use vendor\db\IMySQLDriver;

/**
 * Class SearchMysql wraps the vendor-provided MySQL driver
 * @package foo\models\Product
 */
class SearchMysql implements ISearch {

    /** @var IMySQLDriver */
    private $driver;

    public function __construct(IMySQLDriver $mySQLDriver)
    {
        $this->driver = $mySQLDriver;
    }

    /**
     * @param string $id - key to search for
     * @return array|NULL - result if found, NULL if not
     */
    public function fetch($id)
    {
        return $this->driver->findProduct($id);
    }
}