<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\DataFixtrues\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use g5\MovieBundle\Entity\Label;

class LoadLabelData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('test-user');

        $label1 = new Label();
        $label1->setName('ATest 1A');
        $label1->setUser($user);

        $label2 = new Label();
        $label2->setName('ATest 1B');
        $label2->setUser($user);

        $labelHorror = new Label();
        $labelHorror->setName('Horror');
        $labelHorror->setUser($user);

        $manager->persist($label1);
        $manager->persist($label2);
        $manager->persist($labelHorror);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
