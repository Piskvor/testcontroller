<?php

namespace foo\models\Cache;

use foo\models\Datastore\IBackend;

/**
 * Interface IFrontend: caches the data coming from IFetchable source, transparently saves them to cache
 * @package foo\models\Cache
 */
interface IFrontend
{

    /**
     * @param IFetchable $fetchable - an interface to fetch the uncached data from
     * @return void
     */
    public function setSource(IFetchable $fetchable);

    /**
     * @param IBackend $backend - a backend to use for storing the cache
     * @return void
     */
    public function setBackend(IBackend $backend);

    /**
     * @param string $id - the key to search for.
     * If cached by IBackend, it will be returned from cache;
     * else it will be queried from the IFetchable AND stored to cache.
     * @return mixed|NULL - the result of the query. If not found, NULL is returned.
     */
    public function fetch($id);

}