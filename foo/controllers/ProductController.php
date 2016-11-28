<?php

namespace foo\controllers;

use foo\config\IConfig;
use foo\helpers\RegistryHelper;
use foo\models\cache\SearchFrontend;
use foo\models\product\PopularityKeeperFrontend;
use vendor\db\IElasticSearchDriver;
use vendor\db\IMySQLDriver;

class ProductController
{

    /** @var SearchFrontend - transparent cache for the search driver */
    private $cachedSearchDriver;

    /** @var PopularityKeeperFrontend - transparent cache for the search driver */
    private $popularityKeeper;

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
        // plus we don't need to keep the other driver(s), and can add more if needed
        $searchDriver = RegistryHelper::getSearchDriver($config, $elasticSearchDriver, $mySQLDriver);

        $this->cachedSearchDriver = RegistryHelper::getSearchCache($config, $searchDriver);

        $this->popularityKeeper = RegistryHelper::getPopularityKeeper($config);
    }

    /**
     * @param string $id - ID of the product
     * @return string - product data as a JSON string
     */
    public function detailAction($id)
    {
        if ($this->getHeader('HTTP_IF_MODIFIED_SINCE')) { // user's browser already has this
            $this->popularityKeeper->increment($id);
            header('HTTP/1.1 304 Not Modified');
        } else { // new user
            $result = $this->cachedSearchDriver->fetch($id);
            if ($result) { // we get data! (we don't know whence they came - cache,MySQL,ES - but we don't care)
                $this->popularityKeeper->increment($id);
                header('Content-Type: application/json');
                return json_encode($result);
            } else { // Will never ever be needed? At least for testing backends, let's keep this in.
                header('HTTP/1.1 404 Not Found');
            }
        }
        return ''; // if we are here, there is no (new) data to send anyway
    }

    protected function getHeader($headerName) {
        if (!isset($_SERVER[$headerName])) {
            return null;
        } else {
            return $_SERVER[$headerName];
        }
    }
}
