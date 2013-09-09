<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\HomeBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class DefaultControllerTest extends \g5WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Welcome to Movie Nightmare")')->count() > 0);
    }

    public function testIndexLogedIn()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Top Labels")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Latest Movies")')->count());
    }
}
