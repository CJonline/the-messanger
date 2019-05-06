<?php

namespace App\MessageMiddleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class EmailNotificationMiddleware implements MiddlewareInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     * @throws \Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $this->mailer->send(
            (new \Swift_Message('Email Notification'))
                ->setTo('example@mailservice.com')
                ->setBody(
                    $message->getContent(),
                    'text/html'
                )
        );

        return $envelope;
    }
}