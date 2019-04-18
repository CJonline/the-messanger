<?php

namespace App\Tests\Functional\Api;

use App\Tests\Functional\AbstractBaseTestCase;

class TokenTest extends AbstractBaseTestCase
{

    /**
     * test getPagesAction
     */
    public function testResetToken()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/pages');
    }
}