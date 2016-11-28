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