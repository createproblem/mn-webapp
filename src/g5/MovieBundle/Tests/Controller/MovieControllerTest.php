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
    public function testIndexAction()
    {
        $client = static::createClient();
        $this->login($client);

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->once())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
            $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        });

        $crawler = $client->request('GET', '/movie/');

        $this->assertEquals(1, $crawler->filter('html:contains("Fight Club")')->count());
    }

    public function testNewAction()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/movie/new');

        $this->assertEquals(1, $crawler->filter('form #g5_movie_search_search')->count());
    }

    public function testSearchTmdbWithExceptionAction()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/movie/search');

        $this->assertTrue($client->getResponse()->isNotFound());

        $client->request(
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

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testSearchTmdbAction()
    {
        $client = static::createClient();
        $this->login($client);

        $token = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_search');

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

        $crawler = $client->request('POST', '/movie/search',
            array('g5_movie_search' => array(
                '_token' => $token,
                'search' => 'Fight Club'
            )),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Fight Club")')->count());
    }

    public function testAddAction()
    {
        $client = static::createClient();
        $this->login($client);

        $movieManagerMock = $this->getMockBuilder('g5\MovieBundle\Service\MovieManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $movieManagerMock->expects($this->any())
            ->method('createMovieFromTmdb')
            ->with(8413)
            ->will($this->returnValue($this->createTestMovieEventHorizon()))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($movieManagerMock) {
            $kernel->getContainer()->set('g5_movie.movie_manager', $movieManagerMock);
        });

        $client->request('POST', '/movie/add', array('tmdbId' => 8413));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(8413, $client->getResponse()->getContent());

        static::$kernel->setKernelModifier(function($kernel) use ($movieManagerMock) {
            $kernel->getContainer()->set('g5_movie.movie_manager', $movieManagerMock);
        });

        $client->request('POST', '/movie/add', array('tmdbId' => 8413));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals('["This value is already used."]', $client->getResponse()->getContent());

        $this->delTestMovieEventHorizon();
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
}
