<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class EmailNotificationConsumer implements ConsumerInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $message = (new \Swift_Message('Simple Notification Email'))
            ->setFrom('sender@example.com')
            ->setTo('recipient@example.com')
            ->setBody($msg->getBody());

        $this->mailer->send($message);
    }
}