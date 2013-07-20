<?php

/*
* This file is part of the mn-webaoo package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Tests\Service;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

use g5\MovieBundle\Service\MovieManager;

class MovieManagerTest extends \KernelAwareTest
{
    public function testCreateMovieFromTmdb()
    {
        $tmdbMock = $this->getMockBuilder('g5\ToolsBundle\Tmdb\TmdbApi')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $tmdbMock->expects($this->once())
            ->method('getMovie')
            ->with(550)
            ->will($this->returnValue(json_decode($this->getTestDataDir().'/tmdb_movie_response.json'), true))
        ;

        $doctrine = $this->container->get('doctrine');

        $movieManager = new MovieManager($tmdbMock, $doctrine);
        $movie = $movieManager->createMovieFromTmdb(550);

        $this->assertEquals(550, $movie->getTmdbId());
    }
}
