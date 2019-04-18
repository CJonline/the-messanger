<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(EmailNotification $message)
    {
        // do something with it.
    }
}