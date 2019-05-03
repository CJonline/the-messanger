<?php

namespace App;

use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;

class EmailNotificationReceiver implements ReceiverInterface
{
    private $mailer;
    private $serializer;
    private $connection;
    private $shouldStop;

    public function __construct(Connection $connection, SerializerInterface $serializer = null)
    {
        $this->connection = $connection;
        $this->serializer = $serializer ?? Serializer::create();
    }

    /**
     * Receive some messages to the given handler.
     * The handler will have, as argument, the received {@link \Symfony\Component\Messenger\Envelope} containing the message.
     * Note that this envelope can be `null` if the timeout to receive something has expired.
     */
    public function receive(callable $handler): void
    {
        while (!$this->shouldStop) {
            $AMQPEnvelope = $this->connection->get();

            $handler($this->serializer->decode([
                'body' => $AMQPEnvelope->getBody(),
                'headers' => $AMQPEnvelope->getHeaders(),
            ]));
        }

        $this->mailer->send(
            (new \Swift_Message('Email Notification'))
                ->setTo('example@mailservice.com')
                ->setBody(
                    'Message content: '.$AMQPEnvelope->getContent(),
                    'text/html'
                )
        );
    }

    /**
     * Stop receiving some messages.
     */
    public function stop(): void
    {
        $this->shouldStop = true;
    }
}