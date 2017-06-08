<?php

namespace foo\models\cache;

use foo\models\Datastore\IBackend;
use foo\models\Product\ISearch;

/**
 * Class SearchFrontend - a caching frontend for the search
 * @package foo\models\cache
 */
class SearchFrontend implements IFrontend
{

    /** @var IBackend - the datastore for cache */
    private $cacheDatastore;

    /** @var ISearch - the datastore that is queried non-cached */
    private $searchDatastore;

    public function __construct(ISearch $search, IBackend $backend)
    {
        $this->setSource($search);
        $this->setBackend($backend);
    }

    /**
     * @param IFetchable $fetchable - an interface from which we fetch the uncached data
     * @return void
     * @throws \foo\exceptions\InvalidParameterException
     */
    public function setSource(IFetchable $fetchable)
    {
        $this->searchDatastore = $fetchable;
    }

    /**
     * @param IBackend $backend - a backend to use for storing the cache
     * @return void
     */
    public function setBackend(IBackend $backend)
    {
        $this->cacheDatastore = $backend;
    }

    /**
     * @param string $id - the key to search for.
     * If cached by IBackend, it will be returned from cache;
     * else it will be queried from the IFetchable AND stored to cache.
     * @return mixed|NULL - the result of the query. If not found, NULL is returned.
     */
    public function fetch($id)
    {
        $result = $this->cacheDatastore->fetch($id);
        if (!$result) { // not in cache - we are not expecting a falsy result, ever
            $result = $this->searchDatastore->fetch($id);
            if ($result) { // and exists in backend - again, the backend is not expected to return a falsy value
                $this->cacheDatastore->save($id, $result);
            }
        }
        return $result;
    }
}
