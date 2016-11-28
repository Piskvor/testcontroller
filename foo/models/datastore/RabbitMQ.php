<?php

namespace foo\models\datastore;

use foo\exceptions\ConnectionException;
use PhpAmqpLib\Connection\AMQPStreamConnection; // TODO: not included in the example
use PhpAmqpLib\Message\AMQPMessage; // TODO: not included in the example

/**
 * Class RabbitMQ sends a message to the configured RabbitMQ queue.
 * @package foo\models\datastore
 */
class RabbitMQ extends AbstractDataStore
{


    /** @var \PhpAmqpLib\Connection\AMQPChannel */
    private $channel;

    /**
     * Connect to the backend
     * @return bool - true on success, false on error
     */
    protected function connectBackend()
    {
        $connection = new AMQPStreamConnection($this->config->getItem('rabbitmq.host'),
            $this->config->getItem('rabbitmq.port'),
            $this->config->getItem('rabbitmq.user'),
            $this->config->getItem('rabbitmq.pass'));
        $this->channel = $connection->channel();
        $this->channel->queue_declare($this->config->getItem('rabbitmq.queue'), false, false, false, false);
        return (bool) $this->channel;
    }

    /**
     * RabbitMQ is a write-only backend here
     * @param string|int $id
     * @return mixed|NULL
     * @throws \foo\exceptions\ConnectionException
     */
    public function fetch($id)
    {
        return null;
    }

    /**
     * Send the data through RabbitMQ.
     * @param $id
     * @param $data
     * @param int $expiration
     * @return mixed
     * @throws \foo\exceptions\ConnectionException
     */
    public function save($id, $data, $expiration = null)
    {
        if (!$this->connect()) {
            throw new ConnectionException('Cannot connect to RabbitMQ!');
        }
        $msg = new AMQPMessage($id);
        return $this->channel->basic_publish($msg, '', $this->config->getItem('rabbitmq.messageType'));
    }
}