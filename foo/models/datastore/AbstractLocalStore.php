<?php

namespace foo\models\datastore;

abstract class AbstractLocalStore extends AbstractDataStore
{
    /** @var string */
    protected $filename;

    /** @var array */
    protected $fileData = null;

    public function __construct($location)
    {
        $this->filename = $location;
    }


    /**
     * @param string $data - the whole file
     * @return array
     */
    abstract protected function unserialize($data);

    /**
     * @param $array - the data to be saved
     * @return string - the whole file
     */
    abstract protected function serialize($array);

    protected function getFileName(
        /** @noinspection PhpUnusedParameterInspection */
        $id) {
        /** if a file, we use one filename for everything
         * overridden by @see \foo\models\datastore\SerializedDirectory
         */
        return $this->filename;
    }
    /**
     * Connect to the backend
     * @return bool - true on success, false on error
     */
    protected function connectBackend()
    {
        return true;
    }

    /**
     * Fetch a result from the backend, as described by the identifier
     * @param string|int $id
     * @return mixed|NULL
     * @throws \foo\exceptions\ConnectionException
     */
    public function fetch($id)
    {
        if (!$this->fileData) {
            $this->fileData = $this->unserialize(file_get_contents($this->getFileName($id)));
        }
        if (!isset($this->fileData[$id])) {
            return null;
        } else {
            return $this->fileData[$id];
        }
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
        $this->fileData[$id] = $data;
        return file_put_contents($this->getFileName($id), $this->serialize($this->fileData));
    }
}