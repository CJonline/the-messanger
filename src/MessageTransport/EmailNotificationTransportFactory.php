<?php

namespace App\MessageTransport;

use Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransportFactory;

class EmailNotificationTransportFactory extends AmqpTransportFactory
{

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'rabbit');
    }
}