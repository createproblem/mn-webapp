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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use g5\MovieBundle\Entity\Label;

class LoadLabelData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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

        $labelData = array(
            array('name' => 'Horror', 'name_norm' => 'horror'),
            array('name' => 'Action', 'name_norm' => 'action'),
            array('name' => 'Top Hits', 'name_norm' => 'top-hits'),
        );

        foreach ($labelData as $data) {
            $label = new Label();
            $label->setName($data['name']);
            $label->setNameNorm($data['name_norm']);
            $label->setUser($user);

            $manager->persist($label);
            $this->addReference('label-'.$label->getnameNorm(), $label);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
