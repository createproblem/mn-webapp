<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class MovieControllerTest extends \g5WebTestCase
{
    public function testNewAction()
    {
        $this->login($this->client);

        $crawler = $this->client->request('GET', '/movie/new');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testNewActionPost()
    {
        $tmdbTestHelper = $this->get('g5_tmdb.api.helper');
        $responseStack = array(
            $tmdbTestHelper->getFixture('search_movie.json'),
            $tmdbTestHelper->getFixture('configuration.json')
        );

        $this->login($this->client);

        $tmdbApi = $this->helper->getTmdbApi($responseStack);
        $this->client->getContainer()->set('g5_tmdb.api.default', $tmdbApi);

        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_search');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $crawler = $this->client->request(
            'POST',
            '/movie/new',
            array('g5_movie_search' => array('search' => 'Fight Club', '_token' => $token))
        );

        $this->assertGreaterThan(1, $crawler->filter('h4:contains("Fight Club")')->count());
    }
}
