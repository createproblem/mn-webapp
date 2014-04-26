<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Tests\Components\Api;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

use g5\TmdbBundle\Components\Api\TmdbApiClient;

class TmdbApiClientTest extends \PHPUnit_Framework_TestCase
{
    private $tmdbApi = null;
    private $useRealApi = false;
    private $fixtures;

    public function setUp()
    {
        parent::setUp();

        $this->fixtures = dirname(__FILE__).'/../../../Resources/fixtures/response';
        $this->tmdbApi = TmdbApiClient::factory(array('api_key' => $_SERVER['tmdb.api_key']));
        $this->useRealApi = (bool) $_SERVER['tmdb.use_real_api'];
    }

    public function testGetConfiguration()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/configuration.json'));
        $result = $this->tmdbApi->getConfiguration();

        $this->assertInstanceOf('Guzzle\Service\Resource\Model', $result);
    }

    public function testGetSearchMovie()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/search_movie.json'));
        $result = $this->tmdbApi->getSearchMovie(array('query' => 'Fight Club'));

        $this->assertEquals(550, $result['results'][0]['id']);
    }

    /**
     * @expectedException           Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage    Validation errors: [query] is required
     */
    public function testGetSearchMovieValidationException()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/search_movie.json'));
        $result = $this->tmdbApi->getSearchMovie();
    }

    public function testGetMovie()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/movie.json'));
        $result = $this->tmdbApi->getMovie(array('id' => 550));

        $this->assertInstanceOf('Guzzle\Service\Resource\Model', $result);
        $this->assertEquals(550, $result['id']);
    }

    public function testGetMovieAppendToResponse()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/movie_append_to_response.json'));
        $result = $this->tmdbApi->getMovie(array('id' => 550, 'append_to_response' => 'images'));

        $this->assertInstanceOf('Guzzle\Service\Resource\Model', $result);
        $this->assertEquals(550, $result['id']);
        $this->assertArrayHasKey('images', $result);
    }

    /**
     * @expectedException           Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage    Validation errors: [id] is a required integer
     */
    public function testGetMovieValidationException()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/movie.json'));
        $result = $this->tmdbApi->getMovie();
    }

    public function testGetMovieImages()
    {
        $client = $this->getTmdbApiClient(file_get_contents($this->fixtures.'/movie_images.json'));
        $result = $this->tmdbApi->getMovieImages(array('id' => 550));

        $this->assertInstanceOf('Guzzle\Service\Resource\Model', $result);
        $this->assertEquals(550, $result['id']);
        $this->assertArrayHasKey('backdrops', $result);
    }

    private function getTmdbApiClient($body, $status = 200)
    {
        if (null === $this->tmdbApi) {
            $this->tmdbApi = TmdbApiClient::factory(array('api_key' => $_SERVER['tmdb.api_key']));
        }

        if (!$this->useRealApi) {
            $this->tmdbApi->addSubscriber($this->getMockResponsePlugin($body, $status));
        }
    }

    private function getMockResponsePlugin($body, $status)
    {
        $plugin = new MockPlugin();
        $response = new Response($status);
        $response->setBody($body);

        $plugin->addResponse($response);

        return $plugin;
    }
}
