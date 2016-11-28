<?php

namespace foo\models\datastore;


use foo\config\IConfig;

/**
 * Class AbstractDataStore handles the shared functionality: config setting and checking for connection to the backend
 * @package foo\models\datastore
 */
abstract class AbstractDataStore implements IBackend
{
    /**
     * @var IConfig
     */
    protected $config;

    /**
     * @var bool - if true, we are connected to the backend
     */
    private $isConnected = false;

    /**
     * @param IConfig $config settings for the current backend
     * @return void
     */
    public function injectConfig(IConfig $config)
    {
        $this->config = $config;
    }

    abstract public function fetch($id);

    abstract public function save($id, $data, $expiration = null);

    /**
     * Check if connected to the backend and do so if not
     * @return bool - true on success, false on error
     */
    protected function connect() {
        if (!$this->isConnected) {
            $this->isConnected = $this->connectBackend();
        }
        return $this->isConnected;
    }

    /**
     * Connect to the backend - implemented by the specific driver
     * @todo: perhaps throw exceptions on connect instead of returning false?
     * @return bool - true on success, false on error
     */
    abstract protected function connectBackend();

}