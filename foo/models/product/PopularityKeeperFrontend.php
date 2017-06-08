<?php

namespace foo\models\product;


use foo\models\Datastore\IBackend;

/**
 * Class PopularityKeeperFrontend - keeps the
 * @package foo\models\product
 */
class PopularityKeeperFrontend
{
    /**
     * @var IBackend
     */
    private $backend;

    public function __construct(IBackend $popularityKeeperBackend)
    {
        $this->backend = $popularityKeeperBackend;
    }

    /**
     * Increment the popularity - note the race condirion between fetch and save!!!
     * Some backends can avoid this (RabbitMQ and MySQL) by disregarding the `state` data and incrementing atomically
     * @param string $id
     */
    public function increment($id)
    {
        $state = $this->getScore($id);
        if (!$state) {
            $state = 1;
        } else {
            $state++;
        }
        $this->backend->save($id,$state);
    }

    /**
     * Gets the product's popularity score. Public as we need to get the data from the keeper.
     * @param string $id
     * @return int
     */
    public function getScore($id) {
        $state = $this->backend->fetch($id);
        if (!$state) {
            $state = 0;
        }
        return $state;
    }

}
