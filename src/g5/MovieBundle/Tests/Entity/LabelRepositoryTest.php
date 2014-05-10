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

class LabelRepositoryTest extends \KernelAwareTest
{
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->get('g5_movie.label_manager')->repository;
    }

    public function testfindByNameWithLike()
    {
        $user = $this->helper->loadUser('test');

        $labels = $this->repository->findByNameWithLike('h', $user);

        $this->assertEquals('horror', $labels[0]->getNameNorm());
    }

    public function testFindByNamesNorm()
    {
        $find = array('horror', 'top-hits');
        $user = $this->helper->loadUser('test');

        $labels = $this->repository->findByNamesNorm($find, $user);

        foreach ($labels as $label) {
            $this->assertTrue(in_array($label->getNameNorm(), $labels));
        }
    }

    public function testFind()
    {
        $user = $this->helper->loadUser('test');
        $expected = $user->getLabels()[0];

        $label = $this->repository->find($expected->getId(), $user);

        $this->assertEquals($expected, $label);
    }
}
