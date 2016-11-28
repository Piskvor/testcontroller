<?php

namespace foo\models\datastore;

use foo\exceptions\ConnectionException;

class Memcache extends AbstractDataStore
{

    /** @var \Memcache */
    private $mcConnection;

    /**
     * Connect to the backend
     * @todo: perhaps throw exceptions instead of returning false?
     * @return bool - true on success, false on error
     */
    protected function connectBackend()
    {
        $this->mcConnection = new \Memcache($this->config->getItem('memcache.host'),
            $this->config->getItem('memcache.port'));
        return (bool) $this->mcConnection;
    }

    /**
     * Fetch a result from the backend, as described by the identifier
     * @param string|int $id
     * @return mixed|NULL
     * @throws \foo\exceptions\ConnectionException
     */
    public function fetch($id)
    {
        if (!$this->connect()) {
            throw new ConnectionException('Cannot connect to Memcache!');
        }
        return $this->mcConnection->get($id);
    }

    /**
     * Save a result to the backend
     * @param $id
     * @param $data
     * @param int $expiration
     * @return mixed
     * @throws \foo\exceptions\ConnectionException
     */
    public function save($id, $data, $expiration = null)
    {
        if (!$this->connect()) {
            throw new ConnectionException('Cannot connect to Memcache!');
        }
        return $this->mcConnection->set($id, $data, 0, (int)$expiration);
    }
}