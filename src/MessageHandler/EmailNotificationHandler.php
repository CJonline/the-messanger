<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class EmailNotificationHandler implements MessageSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * EmailNotificationHandler constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Returns a list of messages to be handled.
     * It returns a list of messages like in the following example:
     *     yield MyMessage::class;
     * It can also change the priority per classes.
     *     yield FirstMessage::class => ['priority' => 0];
     *     yield SecondMessage::class => ['priority => -10];
     * It can also specify a method, a priority and/or a bus per message:
     *     yield FirstMessage::class => ['method' => 'firstMessageMethod'];
     *     yield SecondMessage::class => [
     *         'method' => 'secondMessageMethod',
     *         'priority' => 20,
     *         'bus' => 'my_bus_name',
     *     ];
     * The benefit of using `yield` instead of returning an array is that you can `yield` multiple times the
     * same key and therefore subscribe to the same message multiple times with different options.
     * The `__invoke` method of the handler will be called as usual with the message to handle.
     */
    public static function getHandledMessages() : iterable
    {
        yield EmailNotification::class => ['method' => 'sendEmail'];
        yield EmailNotification::class => ['method' => 'outputServiceMessage'];
    }

    /**
     * @param EmailNotification $emailNotification
     */
    public function sendEmail(EmailNotification $emailNotification): void
    {
        $this->mailer->send(
            (new \Swift_Message('Email Notification'))
                ->setTo('example@mailservice.com')
                ->setBody(
                    $emailNotification->getContent(),
                    'text/html'
                )
        );
    }

    /**
     * @param EmailNotification $emailNotification
     */
    public function outputServiceMessage(EmailNotification $emailNotification): void
    {
        echo "Notification sent\n";
    }
}
