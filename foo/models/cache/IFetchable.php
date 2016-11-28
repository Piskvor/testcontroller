<?php

namespace foo\models\cache;

/**
 * Interface IFetchable - fetches a primitive type by a string ID
 * @package foo\models\cache
 */
interface IFetchable
{
    /**
     * @param string $id - key to search for
     * @return string|boolean|int|float|array|NULL - result if found, NULL if not
     */
    public function fetch($id);

}