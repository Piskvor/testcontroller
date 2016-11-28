<?php

namespace foo\helpers;


use foo\config\IConfig;
use foo\exceptions\InvalidParameterException;
use foo\models\cache\SearchFrontend;
use foo\models\Product\ISearch;
use foo\models\Product\SearchElasticDb;
use foo\models\Product\SearchMysql;

class RegistryHelper
{

    /**
     * @param \foo\config\IConfig $config set of config values for the
     * @param \vendor\db\IElasticSearchDriver|NULL $elasticSearchDriver - external ES driver if given
     * @param \vendor\db\IMySQLDriver|NULL $mySQLDriver - external MySQL driver if give
     * @return ISearch
     * @throws \foo\exceptions\InvalidParameterException
     */
    public static function getSearchDriver(IConfig $config, $elasticSearchDriver, $mySQLDriver)
    {
        $searchDriverName = $config->getItem('product.search');
        // we could be more elegant and try autoloading
        // where the `switch` inserts another point of programming complexity
        // OTOH, we are only using a whitelisted set of strings here,
        //  and not instantiating classes directly from user input (i.e. from config strings)
        switch ($searchDriverName) { //
            case 'SearchElasticDb':
                return new SearchElasticDb($elasticSearchDriver);
            case 'SearchMySQL':
                return new SearchMysql($mySQLDriver);
            default:
                throw new InvalidParameterException('Unknown search driver:' . $searchDriverName);
        }

    }

    /**
     * @param \foo\config\IConfig $config set of config values for the cache
     * @param ISearch $searchDriver driver for searching
     * @return SearchFrontend Cache frontend
     */
    public static function getSearchCache(IConfig $config, ISearch $searchDriver)
    {
        $searchDriverName = $config->getItem('cache.backend');
        // again, autoloading would be useful
        switch ($searchDriverName) { //
            case 'Memcache':
                $cacheBackend = new \foo\models\datastore\Memcache();
                break;
            case 'SerializedDirectory':
                $cacheBackend = new \foo\models\datastore\SerializedDirectory($config->getItem('cache.backend.location'));
                break;
            case 'PhpSerializedFile':
                $cacheBackend = new \foo\models\datastore\PhpSerializedFile($config->getItem('cache.backend.location'));
                break;
            case 'JsonFile':
                $cacheBackend = new \foo\models\datastore\JsonFile($config->getItem('cache.backend.location'));
                break;
            default:
                $cacheBackend = new \foo\models\datastore\Null();
        }

        $cacheBackend->injectConfig($config);

        return new SearchFrontend($searchDriver, $cacheBackend);
    }
}