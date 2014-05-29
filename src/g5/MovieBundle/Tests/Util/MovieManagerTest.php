<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Util;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

class MovieManagerTest extends \KernelAwareTest
{
    private $mm;

    public function setUp()
    {
        parent::setUp();

        $this->mm = $this->container->get('g5_movie.movie_manager');
    }

    public function testCreateMovie()
    {
        $this->assertInstanceOf('g5\MovieBundle\Document\Movie', $this->mm->createMovie());
    }

    public function testCreateMovieFromTmdb()
    {
        $data = array(
            'id' => 123,
            'overview' => 'Test Overview',
            'original_title' => 'Test Movie Title',
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'release_date' => '2014-05-05'
        );
        $model = new \Guzzle\Service\Resource\Model($data);
        $movie = $this->mm->createMovieFromTmdb($model);

        $this->assertEquals($data['id'], $movie->getTmdbId());
        $this->assertEquals($data['overview'], $movie->getOverview());
        $this->assertEquals($data['original_title'], $movie->getTitle());
        $this->assertEquals($data['poster_path'], $movie->getPosterPath());
        $this->assertEquals($data['backdrop_path'], $movie->getBackdropPath());
    }

    public function testUpdateMovie()
    {
        $data = array(
            'id' => 123,
            'overview' => 'Test Overview',
            'original_title' => 'Test Movie Title',
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'release_date' => '2014-05-05'
        );
        $model = new \Guzzle\Service\Resource\Model($data);
        $movie = $this->mm->createMovieFromTmdb($model);
        $movie->setUser($this->helper->loadUser('test'));

        $this->mm->updateMovie($movie);

        $this->assertGreaterThan(0, $movie->getId());

        return $movie->getId();
    }

    /**
     * @depends testUpdateMovie
     */
    public function testRemoveMovie($id)
    {
        $movie = $this->mm->repository->find($id);

        $this->mm->removeMovie($movie);
        $compare = $this->mm->repository->find($movie->getId());

        $this->assertNull($compare);
    }
}
