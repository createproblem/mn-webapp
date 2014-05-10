<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Entity\LabelRepository;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

class MovieRepositoryTest extends \KernelAwareTest
{
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->get('g5_movie.movie_manager')->repository;
    }

    public function testFind()
    {
        $user = $this->helper->loadUser('test');
        $expected = $user->getMovies()[0];

        $movie = $this->repository->find($expected->getId(), $user);

        $this->assertEquals($expected, $movie);
    }
}
