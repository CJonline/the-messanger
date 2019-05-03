<?php

namespace App\MessageTransport;

use App\EmailNotificationReceiver;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransport;
use Symfony\Component\Messenger\Transport\TransportInterface;

class EmailNotificationTransport extends AmqpTransport implements TransportInterface
{
    private $connection;
    private $serializer;
    private $receiver;

    /**
     * Receive some messages to the given handler.
     * The handler will have, as argument, the received {@link \Symfony\Component\Messenger\Envelope} containing the message.
     * Note that this envelope can be `null` if the timeout to receive something has expired.
     */
    public function receive(callable $handler): void
    {
        ($this->receiver ?? $this->getReceiver())->receive($handler);
    }

    private function getReceiver()
    {
        return $this->receiver = new EmailNotificationReceiver($this->connection, $this->serializer);
    }
}