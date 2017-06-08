<?php

namespace foo\Config;

use foo\exceptions\InvalidParameterException;

/**
 * Class LocalConfig - a simple implementation of IConfig. A collection is selected on construct, based on environment string.
 * @package foo\Config
 */
class LocalConfig implements IConfig
{
    /** @var array Initial set of config values - may be overridden by children! */
    protected static $availableItemConfigs = [
        'dev' => [
            'cache.backend' => null,

            'product.search' => 'SearchMySql',

            'product.count' => 'JsonFile',
            'product.count.location' => '/some/path/productCount.json'
        ],
        'prod' => [
            'cache.backend' => 'Memcache',
            'memcache.host' => 'localhost',
            'memcache.port' => 11211,

            'product.search' => 'SearchElasticDb',

            'product.count' => 'MySql',
            'product.count.host' => 'localhost',
            'product.count.port' => 3306,
            'product.count.user' => 'foo',
            'product.count.pass' => 'bar', /* TODO: store safer - beyond the scope of this example */
            'product.count.db' => 'product_count'
        ],
        'prodMysql' => [
            'cache.backend' => 'SerializedDirectory',
            'cache.backend.location' => '/some/path/cache/',

            'product.search' => 'SearchMySql',

            'product.count' => 'RabbitMQ',
            'rabbitmq.host' => 'localhost',
            'rabbitmq.port' => 5672,
            'rabbitmq.user' => 'baz',
            'rabbitmq.pass' => 'quux', /* TODO: store safer - beyond the scope of this example */
            'rabbitmq.channel' => 'productSearchUp',
            'rabbitmq.messageType' => '1up',
        ]

    ];

    /**
     * @var array - the current config set
     */
    private $selectedEnvironment;

    /**
     * LocalConfig constructor - returns an appropriate config depending on the environment.
     * @param $environment
     * @throws \foo\exceptions\InvalidParameterException
     */
    public function __construct($environment)
    {
        if (isset(self::$availableItemConfigs[$environment]) && is_array(self::$availableItemConfigs[$environment])) {
            $this->selectedEnvironment = self::$availableItemConfigs[$environment];
            $this->selectedEnvironment['environment'] = $environment;
        } else {
            throw new InvalidParameterException('Unknown environment: ' . $environment);
        }
    }

    /**
     * Get one item from the collection.
     * @param string $configItemName name of the configuration item
     * @param string|NULL $default result to return if not found
     * @return string|boolean|int|float|array|NULL the item value, or NULL if not found. Nonexistent and NULL-value items are equivalent.
     */
    public function getItem($configItemName, $default = null)
    {
        return $this->selectedEnvironment[$configItemName];
    }
}
