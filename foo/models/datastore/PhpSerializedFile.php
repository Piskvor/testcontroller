<?php

namespace foo\models\datastore;


class PhpSerializedFile extends AbstractLocalStore
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

}