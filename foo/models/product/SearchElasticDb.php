<?php

namespace foo\models\Product;

use vendor\db\IElasticSearchDriver;

/**
 * Class SearchElasticDb wraps the vendor-provided ElasticSearch driver
 * @package foo\models\Product
 */
class SearchElasticDb implements ISearch
{

    /** @var IElasticSearchDriver */
    private $driver;

    public function __construct(IElasticSearchDriver $elasticSearchDriver)
    {
        $this->driver = $elasticSearchDriver;
    }

    /**
     * @param string $id - key to search for
     * @return array|NULL - result if found, NULL if not
     */
    public function fetch($id)
    {
        return $this->driver->findById($id);
    }
}