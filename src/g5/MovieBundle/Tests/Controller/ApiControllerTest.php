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
            '/movie/api2/movies/550/tmdb',
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
            '/movie/api2/movies',
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
            '/movie/api2/movies',
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
        $user = $this->loadTestUser();
        $movie = $user->getMovies()[0];

        $this->client->request(
            'GET',
            "/movie/api2/movies/{$movie->getId()}/label/form",
            array('_format' => 'html')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testPostMovieLabelAction()
    {
        $this->login($this->client);
        $user = $this->loadTestUser();
        $movie = $user->getMovies()[0];

        $name = uniqid();
        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_link');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $this->client->request(
            'POST',
            "/movie/api2/movies/{$movie->getId()}/labels",
            array('link' => array('movie_id' => $movie->getId(), 'name' => $name, '_token' => $token))
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $label = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($name, $label['name']);

        return $label;
    }

    /**
     * @depends testPostMovieLabelAction
     */
    public function testPostMovieLabelActionDuplicate($label)
    {
        $this->login($this->client);
        $user = $this->loadTestUser();
        $movie = $user->getMovies()[0];

        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_link');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $this->client->request(
            'POST',
            "/movie/api2/movies/{$movie->getId()}/labels",
            array('link' => array('movie_id' => $movie->getId(), 'name' => $label['name'], '_token' => $token))
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals('{"error":"Label already assigned."}', $this->client->getResponse()->getContent());

        return $label;
    }

    /**
     * @depends testPostMovieLabelActionDuplicate
     */
    public function testDeleteMovieLabelAction($label)
    {
        $this->login($this->client);
        $user = $this->loadTestUser();
        $movie = $user->getMovies()[0];

        $this->client->request(
            'DELETE',
            "/movie/api2/movies/{$movie->getId()}/label?labelId={$label['id']}"
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals('{"status":"ok"}', $this->client->getResponse()->getContent());

        $lm = $this->get('g5_movie.label_manager');
        $lm->removeLabel($lm->find($label['id']));
    }
}
