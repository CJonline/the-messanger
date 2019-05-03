<?php

namespace App\MessageTransport;

use Symfony\Component\Messenger\Transport\AmqpExt\Connection;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class EmailNotificationTransportFactory implements TransportFactoryInterface
{
    private $serializer;
    private $debug;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, SerializerInterface $serializer = null, bool $debug = false)
    {
        $this->serializer = $serializer ?? Serializer::create();
        $this->debug = $debug;
        $this->mailer = $mailer;
    }

    public function createTransport(string $dsn, array $options): TransportInterface
    {
        return new EmailNotificationTransport($this->mailer, Connection::fromDsn($dsn, $options, $this->debug), $this->serializer);
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'amqp://');
    }
}
