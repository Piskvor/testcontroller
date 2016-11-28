<?php

namespace foo\models\datastore;


class JsonFile extends AbstractLocalStore
{

    /**
     * @param string $data - the whole file
     * @return array
     */
    protected function unserialize($data)
    {
        return json_decode($data, true);
    }

    protected function serialize($array)
    {
        return json_encode($array);
    }
}