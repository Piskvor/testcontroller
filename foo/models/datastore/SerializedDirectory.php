<?php

namespace foo\models\datastore;


class SerializedDirectory extends AbstractLocalStore
{

    /**
     * @param string $data - the whole file
     * @return array
     */
    protected function unserialize($data)
    {
        return unserialize($data);
    }

    /**
     * @param $array - the data to be saved
     * @return string - the whole file
     */
    protected function serialize($array)
    {
        return serialize($array);
    }

    /**
     * Note that we are still saving the files in 1 directory
     * For high enough loads, splitting by .{1}/.{1,2}/.{1,3}/.{1,4} etc. is recommended (or consider a different backend)
     * @param $id
     * @return string
     */
    protected function getFileName($id)
    {
        // @TODO: $id is unsanitized user input, clean it up in a way that is more readable w/o backend
        // (the md5() hash here is an ugly hack which avoids directory traversal)
        return $this->filename . DIRECTORY_SEPARATOR . md5($id) . '.cache.txt';
    }

    /**
     * Fetch a result from the backend, as described by the identifier
     * @param string|int $id
     * @return mixed|NULL
     * @throws \foo\exceptions\ConnectionException
     */
    public function fetch($id)
    {
        return $this->unserialize(file_get_contents($this->getFileName($id)));
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
        return file_put_contents($this->getFileName($id), $this->serialize($data));
    }
}