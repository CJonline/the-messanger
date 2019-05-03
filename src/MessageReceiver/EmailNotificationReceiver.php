<?php

namespace App\MessageReceiver;

use Symfony\Component\Messenger\Transport\AmqpExt\Connection;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class EmailNotificationReceiver implements ReceiverInterface
{
    private $mailer;
    private $serializer;
    private $connection;

    public function __construct(Connection $connection, SerializerInterface $serializer = null, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * Receive some messages to the given handler.
     * The handler will have, as argument, the received {@link \Symfony\Component\Messenger\Envelope} containing the message.
     * Note that this envelope can be `null` if the timeout to receive something has expired.
     */
    public function receive(callable $handler): void
    {
        $this->mailer->send(
            (new \Swift_Message('Email Notification'))
                ->setTo('example@mailservice.com')
                ->setBody(
                    'Message content: ',
                    'text/html'
                )
        );
    }

    /**
     * Stop receiving some messages.
     */
    public function stop(): void
    {
        // skip
    }
}
