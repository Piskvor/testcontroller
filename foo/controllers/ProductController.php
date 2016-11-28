<?php

namespace foo\controllers;

use foo\config\IConfig;
use foo\helpers\RegistryHelper;
use foo\models\cache\SearchFrontend;
use vendor\db\IElasticSearchDriver;
use vendor\db\IMySQLDriver;

class ProductController
{

    /** @var SearchFrontend - transparent cache for the search driver */
    private $cachedSearchDriver;

    /**
     * ProductController constructor. The classes in config would probably be instantiated by FW and injected directly;
     * we are foregoing a registry implementation in this example for the sake of brevity.
     * @param IConfig $config set of config values for the
     * @param IElasticSearchDriver|NULL $elasticSearchDriver - external ES driver if given
     * @param IMySQLDriver|NULL $mySQLDriver - external MySQL driver if give
     * @throws \foo\exceptions\InvalidParameterException
     */
    public function __construct(IConfig $config, IElasticSearchDriver $elasticSearchDriver = null, IMySQLDriver $mySQLDriver = null)
    {
        // note: although we could make the switch between the drivers as-needed when searching,
        // searchDriver presents a unified facade from the different drivers
        // so we don't need to keep the selection logic around, and we go through it exactly once
        // plus we don't need to keep the other driver
        $searchDriver = RegistryHelper::getSearchDriver($config, $elasticSearchDriver, $mySQLDriver);

        $this->cachedSearchDriver = RegistryHelper::getSearchCache($config, $searchDriver);
    }

    /**
     * @param string $id - ID of the product
     * @return string - product data as a JSON string
     */
    public function detailAction($id)
    {
        // TODO: HTTP caching?
        // TODO: cached result
        // TODO: uncached result
        // TODO: JSON output
    }

}
