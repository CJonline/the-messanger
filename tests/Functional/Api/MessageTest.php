<?php

namespace App\Tests\Functional\Api;

use App\Tests\Functional\AbstractBaseTestCase;

class MessageTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testSuccessfulRequest($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', $url, ['content' => 'test']);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testIncorrectRequest($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', $url, []);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetList($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', $url, []);

        $this->assertContains('test', $client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testSearchList($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', $url.'/search', ['filter' => 'test']);

        $this->assertContains('test', $client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testEdit($url)
    {
        $newTextValue = 'test123';
        $client = $this->createAuthenticatedClient();

        $client->request('POST', $url.'/'.$this->getMessageId($url), ['content' => $newTextValue]);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $client->request('GET', $url.'/search', ['filter' => $newTextValue]);

        $this->assertContains($newTextValue, $client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testDeleteSingle($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('DELETE', $url, ['id' => $this->getMessageId($url)]);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/api/message'];
    }

    private function getMessageId($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', $url.'/search', []);

        return reset(json_decode($client->getResponse()->getContent())->data)->id;
    }
}