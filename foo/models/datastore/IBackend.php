<?php

namespace foo\models\Datastore;

use foo\config\IConfig;

/**
 * General storage backend.
 * Connection configuration is injected via injectConfig.
 * Read a string using fetch($key)
 * Write a string using save($key, $data, $expiration), where $expiration is optional
 * @package foo\models\Datastore
 */
interface IBackend
{
    /**
     * @param IConfig $config settings for the current backend
     * @return void
     */
    public function injectConfig(IConfig $config);

    /**
     * Fetch a result from the backend, as described by the identifier
     * @param string|int $id - key to look up
     * @return string|NULL - looked up result, or NULL if not found (result with value = NULL is same as "none found")
     */
    public function fetch($id);

    /**
     * Save a string to the backend
     * @param string $id - key to save the data under
     * @param string $data - data to save
     * @param int $expiration - seconds after which to expire the data
     * @return boolean true on success or false on failure
     */
    public function save($id, $data, $expiration = NULL);
}