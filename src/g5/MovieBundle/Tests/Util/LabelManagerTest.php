<?php

/*
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

    public function testFindLabelsBy()
    {
        $expectedLabel = $this->createTestLabel();

        $labels = $this->lm->findLabelsBy(array('id' => $expectedLabel->getId()));

        $this->assertTrue(is_array($labels));
        $this->assertEquals($expectedLabel->getId(), $labels[0]->getId());

        $this->deleteLabel($expectedLabel);
    }

    public function testFindLabelBy()
    {
        $expectedLabel = $this->createTestLabel();

        $label = $this->lm->findLabelBy(array('id' => $expectedLabel->getId()));

        $this->assertEquals($expectedLabel->getId(), $label->getId());

        $this->deleteLabel($expectedLabel);
    }

    public function testFindLabelsByNameWithLike()
    {
        $expectedLabel = $this->createTestLabel();
        $user = $this->loadTestUser();

        $labels = $this->lm->findLabelsByNameWithLike('Test-L', $user);

        $this->assertTrue(is_array($labels));
        $this->assertInstanceOf('g5\MovieBundle\Entity\Label', $labels[0]);
        $this->assertEquals($expectedLabel->getName(), $labels[0]->getName());

        $this->deleteLabel($expectedLabel);
    }

    public function testLoadLabelById()
    {
        $expectedLabel = $this->createTestLabel();
        $user = $this->loadTestUser();

        $label = $this->lm->loadLabelById($expectedLabel->getId(), $user);

        $this->assertEquals($expectedLabel->getId(), $label->getId());
        $this->assertEquals($expectedLabel->getUser()->getId(), $label->getUser()->getId());

        $this->deleteLabel($expectedLabel);
    }

    public function testRemoveLabel()
    {
        $expectedLabel = $this->createTestLabel();

        $this->lm->removeLabel($expectedLabel);

        $this->assertNull($expectedLabel->getId());
    }

    public function testUpdateLabel()
    {
        $expectedLabel = $this->createTestLabel();
        $expectedLabel->setName("Test-AAAA");
        $user = $this->loadTestUser();
        $this->lm->updateLabel($expectedLabel);

        $label = $this->lm->loadLabelById($expectedLabel->getId(), $user);

        $this->assertEquals('Test-AAAA', $label->getName());

        $this->deleteLabel($expectedLabel);
    }

    public function testLoadTopLabels()
    {
        $label = $this->createTestLabel();
        $user = $this->loadTestUser();

        $label->setMovieCount(99);
        $this->lm->updateLabel($label);

        $labels = $this->lm->loadTopLabels($user);

        $this->assertEquals($labels[0]->getId(), $label->getId());

        $this->deleteLabel($label);
    }
}
