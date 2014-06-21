<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Repository;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

class MovieRepositoryTest extends \KernelAwareTest
{
    public function testFindUniqueBy()
    {
        $repository = $this->get('doctrine_mongodb')->getRepository('g5MovieBundle:Movie');
        $user = $this->helper->loadUser('test@example.com');

        $movies = $repository->findUniqueBy(array(
            'user' => $user->getId(),
            'tmdb_id' => 550
        ));

        $this->assertEquals(550, $movies[0]->getTmdbId());
    }
}
