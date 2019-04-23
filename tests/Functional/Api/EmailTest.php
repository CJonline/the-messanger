<?php

namespace App\Tests\Functional\Api;

use App\Tests\Functional\AbstractBaseTestCase;

class EmailTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testSuccessfulRequest($url)
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', $url, ['message' => 'test']);

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

    public function urlProvider()
    {
        yield ['/api/email'];
    }
}