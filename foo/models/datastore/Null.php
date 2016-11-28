<?php

namespace foo\models\datastore;

/**
 * Null cache - no caching at all (for testing purposes)
 * @package foo\models\datastore
 */
class Null extends AbstractDataStore
{

    /**
     * Connect to the backend
     * @return bool - true on success, false on error
     */
    protected function connectBackend()
    {
        return true;
    }

    /**
     * Fetch a result from the backend, which obviously never happens
     * @param string|int $id
     * @return mixed|NULL
     */
    public function fetch($id)
    {
        return null;
    }

    /**
     * Save a result to the backend - faked here.
     * @param $id
     * @param $data
     * @param int $expiration
     * @return mixed
     */
    public function save($id, $data, $expiration = null)
    {
        return true;
    }
}