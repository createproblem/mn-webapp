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
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Movie Nightmare")')->count() > 0);
    }

    public function testIndexLogedIn()
    {
        $this->login($this->client);

        $tmdbTestHelper = $this->get('g5_tmdb.api.helper');
        $api = $this->helper->getTmdbApi(array($tmdbTestHelper->getFixture('configuration.json')));
        $this->client->getContainer()->set('g5_tmdb.api.default', $api);

        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h4:contains("Fight Club")')->count());
    }
}
