<?php

namespace App\MessageTransport;

use App\MessageReceiver\EmailNotificationReceiver;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransport;
use Symfony\Component\Messenger\Transport\AmqpExt\Connection;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class EmailNotificationTransport extends AmqpTransport implements TransportInterface
{
    private $connection;
    private $serializer;
    private $receiver;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, Connection $connection, SerializerInterface $serializer = null)
    {
        $this->connection = $connection;
        $this->serializer = $serializer ?? Serializer::create();

        $this->mailer = $mailer;
    }

    /**
     * Receive some messages to the given handler.
     * The handler will have, as argument, the received {@link \Symfony\Component\Messenger\Envelope} containing the message.
     * Note that this envelope can be `null` if the timeout to receive something has expired.
     */
    public function receive(callable $handler): void
    {
        ($this->receiver ?? $this->getReceiver())->receive($handler);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): void
    {
        ($this->receiver ?? $this->getReceiver())->stop();
    }

    private function getReceiver()
    {
        return $this->receiver = new EmailNotificationReceiver($this->connection, $this->serializer, $this->mailer);
    }
}
