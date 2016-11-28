<?php

namespace foo\models\product;


use foo\models\Datastore\IBackend;

class PopularityKeeperFrontend
{
    private $backend;

    public function __construct(IBackend $popularityKeeperBackend)
    {
        $this->backend = $popularityKeeperBackend;
    }

    /**
     * Increment the popularity - note the race condirion between fetch and save!!!
     * Some backends avoid this (RabbitMQ and MySQL) by disregarding the `state` data and incrementing atomically
     * @param string $id
     */
    public function increment($id)
    {
        $state = $this->backend->fetch($id);
        if (!$state) {
            $state = 1;
        } else {
            $state++;
        }
        $this->backend->save($id,$state);
    }

}