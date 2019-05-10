<?php

namespace App\Tests\Functional\Api;

use App\Tests\Functional\AbstractBaseTestCase;
class EmailTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testSuccessfulRequest(string $url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', $url, ['message' => 'test']);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testIncorrectRequest(string $url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testMessageSent(string $url)
    {
        $client = $this->createAuthenticatedClient();
        $client->enableProfiler();

        $client->request('POST', $url, ['message' => 'test message']);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Email Notification', $message->getSubject());
        $this->assertSame('example@mailservice.com', key($message->getTo()));
        $this->assertSame(
            'test message',
            $message->getBody()
        );
    }

    /**
     * @return \Generator
     */
    public function urlProvider()
    {
        yield ['/api/email'];
    }
}