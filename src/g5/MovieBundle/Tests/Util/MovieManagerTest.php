<?php

/*
* This file is part of the mn-webaoo package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Tests\Util;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

use g5\MovieBundle\Util\MovieManager;

class MovieManagerTest extends \KernelAwareTest
{
    /**
     * @var g5\MovieBundle\Util\MovieManager
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

    public function testFindMoviesBy()
    {
        $expectedMovie = $this->createTestMovie();
        $movies = $this->mm->findMoviesBy(array('id' => $expectedMovie));

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

    public function testFindMoviesByLabel()
    {
        $label = $this->createTestLabel();
        $movie = $this->createTestMovie();

        $movie->addLabel($label);
        $this->mm->updateMovie($movie);

        $movies = $this->mm->findMoviesByLabel($label);
        $this->assertEquals(1, count($movies));

        $movies = $this->mm->findMoviesByLabel($label, null, 1, 0);
        $this->assertEquals(1, count($movies));

        $this->deleteLabel($label);
        $this->deleteMovie($movie);
    }

    public function testLoadLatestMovies()
    {
        $user = $this->loadTestUser();
        $movie = $this->createTestMovie();
        $movies = $this->mm->loadLatestMovies($user);

        $this->assertEquals($movies[0]->getId(), $movie->getId());

        $this->deleteMovie($movie);
    }

    public function testLoadRandomMovies()
    {
        $user = $this->loadTestUser();
        $movies = $this->mm->loadRandomMovies($user, 1);

        $this->assertEquals(1, count($movies));

        $movies = $this->mm->loadRandomMovies($user, 4);

        $this->assertEquals(4, count($movies));
    }

    public function testFindMoviesWithoutLabel()
    {
        $user = $this->loadTestUser();
        $movies = $this->mm->findMoviesWithoutLabel($user);

        $this->assertEquals(0, count($movies));

        $movie = $this->createTestMovie();
        $movies = $this->mm->findMoviesWithoutLabel($user);

        $this->assertEquals(1, count($movies));

        $this->deleteMovie($movie);
    }

    public function testLoadMoviesByFavorite()
    {
        $user = $this->loadTestUser();
        $movie = $this->createTestMovie();

        $movie->setFavorite(true);
        $this->mm->updateMovie($movie);

        $movies = $this->mm->loadMoviesByFavorite($user);

        $this->assertGreaterThan(0, count($movies));

        $this->deleteMovie($movie);
    }
}
