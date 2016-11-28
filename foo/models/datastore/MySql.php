<?php

namespace foo\models\datastore;

use foo\exceptions\ConnectionException;

/**
 * Class Mysql used for the popularity keeper
 * @TODO: refactor this to be usable as a normal backend
 * @package foo\models\datastore
 */
class Mysql extends AbstractDataStore
{

    /** @var \mysqli */
    private $mysqliConnection;

    /** @var \mysqli_stmt */
    private $prepQuery;

    /** @var \mysqli_stmt */
    private $prepSave;

    /**
     * Connect to the backend
     * @todo: perhaps throw exceptions instead of returning false?
     * @return bool - true on success, false on error
     */
    protected function connectBackend()
    {
        $this->mysqliConnection = new \mysqli($this->config->getItem('product.count.host'),
            $this->config->getItem('product.count.port'), $this->config->getItem('product.count.user'),
            $this->config->getItem('product.count.pass'));
        if ($this->mysqliConnection) {
            $this->mysqliConnection->select_db($this->config->getItem('product.count.db'));
        }
        $this->prepQuery = $this->mysqliConnection->prepare('SELECT product_count FROM sometable WHERE product_id = ?');
        $this->prepSave = $this->mysqliConnection->prepare('UPDATE sometable SET product_count = product_count + 1 WHERE product_id = ?'); // default should be 0
        return (bool)$this->mysqliConnection;
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
        $result = null;
        $this->prepQuery->bind_param('s', $id);
        $this->prepQuery->execute();
        $this->prepQuery->bind_result($result);
        $this->prepQuery->fetch();
        return $result;
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
        $this->prepSave->bind_param('s', $id);
        return $this->prepSave->execute();
    }
}