<?php
// /src/g5/MovieBundle/Tests/Controller/MovieControllerTest.php

/*
* This file is part of the mn-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien/>
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
        $this->createMovie($client, 'test');

        $tmdbMock = $this->getTmdbMock();

        $client->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);

        $crawler = $client->request('GET', '/movie/');

        $this->assertEquals(1, $crawler->filter('html:contains("Fight Club")')->count());
    }

    public function testNewAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/movie/new/');

        $this->assertEquals(1, $crawler->filter('input[placeholder="Movie Title"]')->count());
    }

    private function getTmdbMock()
    {
        $tmdbMock = $this->getMockBuilder('g5\ToolsBundle\Tmdb\TmdbApi')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $tmdbMock->expects($this->any())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        return $tmdbMock;
    }
}
