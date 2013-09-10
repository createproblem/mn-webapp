<?php

/*
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
    private $mm;
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->mm = $this->client->getContainer()->get('g5_movie.movie_manager');
    }

    public function testIndexAction()
    {
        $this->login($this->client);

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->once())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
            $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        });

        $crawler = $this->client->request('GET', '/movie');

        $this->assertGreaterThan(1, $crawler->filter('h4')->count());
    }

    public function testNewAction()
    {
        $this->login($this->client);

        $crawler = $this->client->request('GET', '/movie/new');

        $this->assertEquals(1, $crawler->filter('form #g5_movie_search_search')->count());
    }

    public function testSearchTmdbWithExceptionAction()
    {
        $this->login($this->client);

        $this->client->request('GET', '/movie/search');

        $this->assertTrue($this->client->getResponse()->isNotFound());

        $this->client->request(
            'POST',
            '/movie/search',
            array(
                'g5_movie_search[search]' => 'Fight Club',
            ),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            )
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testSearchTmdbAction()
    {
        $this->login($this->client);

        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_search');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $movieSearchResult = json_decode(file_get_contents($this->getTestDataDir().'/tmdb_search_movie_response.json'), true);

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->once())
            ->method('searchMovie')
            ->with(array('query' => 'Fight Club'))
            ->will($this->returnValue($movieSearchResult))
        ;

        $tmdbMock->expects($this->once())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
            $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        });

        $crawler = $this->client->request('POST', '/movie/search',
            array('g5_movie_search' => array(
                '_token' => $token,
                'search' => 'Fight Club'
            )),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Fight Club")')->count());
    }

    public function testAddAction()
    {
        $this->login($this->client);

        $expectedMovie = $this->createTestMovie(false);

        $movieManagerMock = $this->getMovieManagerMock();
        $movieManagerMock->expects($this->any())
            ->method('createMovieFromTmdb')
            ->with(9070)
            ->will($this->returnValue($expectedMovie))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($movieManagerMock) {
            $kernel->getContainer()->set('g5_movie.movie_manager', $movieManagerMock);
        });


        $this->client->request('POST', '/movie/add', array('tmdbId' => 9070));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($expectedMovie->getTmdbId(), $this->client->getResponse()->getContent());
    }

    public function testAddActionWithDuplicated()
    {
        $this->login($this->client);

        $expectedMovie = $this->createTestMovie();

        $movieManagerMock = $this->getMovieManagerMock();
        $movieManagerMock->expects($this->any())
            ->method('createMovieFromTmdb')
            ->with(9070)
            ->will($this->returnValue($expectedMovie))
        ;

        // Set Mock MovieManager
        static::$kernel->setKernelModifier(function($kernel) use ($movieManagerMock) {
            $kernel->getContainer()->set('g5_movie.movie_manager', $movieManagerMock);
        });


        $this->client->request('POST', '/movie/add', array('tmdbId' => 9070));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals('["This value is already used."]', $this->client->getResponse()->getContent());

        // Reset MovieManager
        $mm = $this->mm;
        static::$kernel->setKernelModifier(function($kernel) use ($mm) {
            $kernel->getContainer()->set('g5_movie.movie_manager', $mm);
        });
        static::$kernel->boot();

        $this->deleteMovie($expectedMovie);
    }

    public function testLoadTmdbAction()
    {
        $client = static::createClient();
        $this->login($client);

        $expected = file_get_contents($this->getTestDataDir().'/tmdb_movie_response.json', true);

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->once())
            ->method('getMovie')
            ->with(550)
            ->will($this->returnValue($expected))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
            $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        });

        $client->request('POST', '/movie/loadTmdb', array('tmdbId' => 550));
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testUnlabeledAction()
    {
        $this->login($this->client);
        $movie = $this->createTestMovie();

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->once())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        $this->client->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);

        $crawler = $this->client->request('GET', '/movie/unlabeled');

        $this->assertGreaterThan(0, $crawler->filter('h4')->count());

        $this->deleteMovie($movie);
    }
}
