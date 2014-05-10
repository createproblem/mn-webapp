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

class ApiControllerTest extends \g5WebTestCase
{
    private $tmdbTestHelper;

    public function setUp()
    {
        parent::setUp();

        $this->tmdbTestHelper = $this->get('g5_tmdb.api.helper');
    }

    public function testGetMovieTmdbAction()
    {
        $this->login($this->client);

        $responseStack = array($this->tmdbTestHelper->getFixture('movie.json'));
        $tmdbApi = $this->helper->getTmdbApi($responseStack);
        $this->client->getContainer()->set('g5_tmdb.api.default', $tmdbApi);

        $this->client->request(
            'GET',
            '/movie/api/movies/550/tmdb',
            array('tmdbId' => 550),
            array()
            // array('HTTP_X-Requested-With' => 'XMLHttpRequest')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testPostMovieActionWithError()
    {
        $this->login($this->client);

        $responseStack = array($this->tmdbTestHelper->getFixture('movie.json'));
        $tmdbApi = $this->helper->getTmdbApi($responseStack);
        $this->client->getContainer()->set('g5_tmdb.api.default', $tmdbApi);

        $this->client->request(
            'POST',
            '/movie/api/movies',
            array('tmdbId' => 550),
            array()
            // array('HTTP_X-Requested-With' => 'XMLHttpRequest')
        );

        $error = $this->client->getResponse()->getContent();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals('[{"property_path":"tmdb_id","message":"Movie already in your database."}]', $error);
    }

    public function testPostMovieAction()
    {
        $this->login($this->client);

        $responseStack = array($this->tmdbTestHelper->getFixture('movie_powerrangers.json'));
        $tmdbApi = $this->helper->getTmdbApi($responseStack);
        $this->client->getContainer()->set('g5_tmdb.api.default', $tmdbApi);

        $this->client->request(
            'POST',
            '/movie/api/movies',
            array('tmdbId' => 550),
            array()
            // array('HTTP_X-Requested-With' => 'XMLHttpRequest')
        );

        $movie = $this->client->getResponse()->getContent();
        $movie = json_decode($movie, true);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $movie['id']);

        $mm = $this->get('g5_movie.movie_manager');
        $mm->removeMovie($mm->find($movie['id']));
    }

    public function testGetMovieLabelFormAction()
    {
        $this->login($this->client);
        $user = $this->helper->loadUser('test');
        $movie = $user->getMovies()[0];

        $this->client->request(
            'GET',
            "/movie/api/movies/{$movie->getId()}/label/form",
            array('_format' => 'html')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testPostMovieLabelAction()
    {
        $this->login($this->client);
        $user = $this->helper->loadUser('test');
        $movie = $user->getMovies()[0];

        $name = uniqid();
        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_link');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $crawler = $this->client->request(
            'POST',
            "/movie/api/movies/{$movie->getId()}/labels.html",
            array('link' => array('movie_id' => $movie->getId(), 'name' => $name, '_token' => $token))
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('span')->count());

        return $name;
    }
}
