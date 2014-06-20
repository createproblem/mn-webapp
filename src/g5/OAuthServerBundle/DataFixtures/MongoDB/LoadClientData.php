<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\OAuthServerBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use g5\OAuthServerBundle\Document\Client;

class LoadClientData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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

        $client = new Client();
        $client->setName('TestClient');
        $client->setRedirectUris(array('http://localhost:8000', 'http://localhost:9000'));
        $client->setAllowedGrantTypes(array('refresh_token', 'password', 'client_credentials', 'code'));
        $manager->persist($client);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
