<?php

namespace foo\Config;

/**
 * Interface IConfig
 * @package foo\Config
 *
 * Interface for a collection of config options. The items in the collection should be primitive types, or arrays thereof.
 */
interface IConfig
{
    /**
     * Get one item from the collection.
     * @param string $configItemName name of the configuration item
     * @param string|NULL $default result to return if not found
     * @return array|bool|float|int|NULL|string the item value, or NULL if not found. Nonexistent and NULL-value items are equivalent.
     */
    public function getItem($configItemName, $default = null);

}