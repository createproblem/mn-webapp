<?php
// /src/g5/HomeBundle/Tests/Controller/DefaultControllerTest.php

namespace g5\HomeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Node.js")')->count() > 0);
    }
}
