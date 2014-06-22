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

class LabelManagerTest extends \KernelAwareTest
{
    /**
     * @var g5\MovieBundle\Util\LabelManager
     */
    private $lm;

    public function setUp()
    {
        parent::setUp();

        $this->lm = $this->container->get('g5_movie.label_manager');
    }

    public function testCreateLabel()
    {
        $label = $this->lm->createLabel();

        $this->assertInstanceOf('g5\MovieBundle\Document\Label', $label);
    }

    public function testUpdateLabel()
    {
        $label = $this->lm->createLabel();
        $label->setName(uniqid());
        $label->setUser($this->helper->loadUser('test@example.com'));

        $this->lm->updateLabel($label);

        $this->assertNotNull($label->getId());

        return $label->getId();
    }

    /**
     * @depends testUpdateLabel
     */
    public function testRemoveLabel($id)
    {
        $label = $this->lm->repository->find($id);

        $this->lm->removeLabel($label);
        $compare = $this->lm->repository->find($label->getId());
        $this->assertNull($compare);
    }
}
