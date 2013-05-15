<?php

namespace g5\AccountBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/account/registration');

        $this->assertTrue($crawler->filter('html:contains("Accept my terms.")')->count() > 0);
    }
}
