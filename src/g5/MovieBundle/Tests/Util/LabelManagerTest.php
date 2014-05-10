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

        $this->assertInstanceOf('g5\MovieBundle\Entity\Label', $label);
    }

    public function testUpdateLabel()
    {
        $label = $this->lm->createLabel();
        $label->setName(uniqid());
        $label->setNameNorm(uniqid());
        $label->setUser($this->helper->loadUser('test'));

        $this->lm->updateLabel($label);

        $this->assertNotNull($label->getId());

        return $label->getId();
    }

    /**
     * @depends testUpdateLabel
     */
    public function testFindLabelsBy($id)
    {
        $labels = $this->lm->findLabelsBy(array('id' => $id));

        $this->assertTrue(is_array($labels));
        $this->assertEquals($id, $labels[0]->getId());
    }

    /**
     * @depends testUpdateLabel
     */
    public function testFindLabelBy($id)
    {
        $label = $this->lm->findLabelBy(array('id' => $id));
        $this->assertEquals($id, $label->getId());
    }

    /**
     * @depends testUpdateLabel
     */
    public function testFindLabelsByNameWithLike($id)
    {
        $user = $this->helper->loadUser('test');
        $label = $this->lm->find($id);

        $labels = $this->lm->findLabelsByNameWithLike($label->getName(), $user);

        $this->assertTrue(is_array($labels));
        $this->assertInstanceOf('g5\MovieBundle\Entity\Label', $labels[0]);
        $this->assertEquals($label->getName(), $labels[0]->getName());
    }

    /**
     * @depends testUpdateLabel
     */
    public function testLoadTopLabels($id)
    {
        $user = $this->helper->loadUser('test');
        $label = $this->lm->find($id);

        $label->setMovieCount(99);
        $this->lm->updateLabel($label);

        $labels = $this->lm->loadTopLabels($user);

        $this->assertEquals($labels[0]->getId(), $label->getId());
    }

    /**
     * @depends testUpdateLabel
     */
    public function testFind($id)
    {
        $user = $this->helper->loadUser('test');
        $label = $this->lm->find($id, $user);

        $this->assertEquals($id, $label->getId());
    }

    /**
     * @depends testUpdateLabel
     */
    public function testRemoveLabel($id)
    {
        $label = $this->lm->find($id);

        $this->lm->removeLabel($label);

        $this->assertNull($label->getId());
    }
}
