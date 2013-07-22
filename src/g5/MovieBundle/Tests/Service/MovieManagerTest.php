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
    /**
     * @var g5\MovieBundle\Service\MovieManager
     */
    private $mm;

    public function setUp()
    {
        parent::setUp();

        $this->mm = $this->container->get('g5_movie.movie_manager');
    }

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

    public function testFindMovieBy()
    {
        $expectedMovie = $this->createTestMovie();
        $movies = $this->mm->findMovieBy(array('id' => $expectedMovie));

        $this->assertTrue(is_array($movies));
        $this->assertInstanceOf('g5\MovieBundle\Entity\Movie', $movies[0]);
        $this->assertEquals($expectedMovie->getId(), $movies[0]->getId());

        $this->deleteMovie($expectedMovie);
    }

    public function testLoadMovieByIdWithoutException()
    {
        $expectedMovie = $this->createTestMovie();

        $movie = $this->mm->loadMovieById($expectedMovie->getId());

        $this->assertInstanceOf('g5\MovieBundle\Entity\Movie', $movie);
        $this->assertEquals($expectedMovie->getId(), $movie->getId());

        $user = $this->loadTestUser();

        $movie = $this->mm->loadMovieById($expectedMovie->getId(), $user);

        $this->assertInstanceOf('g5\MovieBundle\Entity\Movie', $movie);
        $this->assertEquals($expectedMovie->getId(), $movie->getId());
        $this->assertEquals($expectedMovie->getUser()->getId(), $movie->getUser()->getId());

        $this->deleteMovie($expectedMovie);
    }

    public function testRemoveMovie()
    {
        $expectedMovie = $this->createTestMovie();

        $this->mm->removeMovie($expectedMovie);

        $this->assertNull($expectedMovie->getId());

        $this->deleteMovie($expectedMovie);
    }

    public function testUpdateMovie()
    {
        $expectedMovie = $this->createTestMovie();

        $expectedMovie->setTitle('Test123');
        $this->mm->updateMovie($expectedMovie);

        $this->assertEquals('Test123', $expectedMovie->getTitle());

        $this->deleteMovie($expectedMovie);
    }
}
