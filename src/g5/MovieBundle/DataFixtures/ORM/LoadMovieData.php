<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\AccountBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use g5\MovieBundle\Entity\Movie;
use g5\MovieBundle\Entity\MovieLabel;

class LoadMovieData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $movie = new Movie();
        $movie->setTmdbId(550);
        $movie->setTitle('Fight Club');
        $movie->setOverview(file_get_contents(dirname(__DIR__).'/../../../../app/Resources/meta/TestData/overview_fightclub.txt'));
        $movie->setReleaseDate(new \DateTime('1999-10-14'));
        $movie->setCoverUrl('/8uO0gUM8aNqYLs1OsTBQiXu0fEv.jpg');
        $movie->setUser($this->getReference('test-user'));

        $movieLabel = new MovieLabel();
        $movieLabel->setMovie($movie);
        $movieLabel->setLabel($this->getReference('label-horror'));
        $movie->addMovieLabel($movieLabel);
        $manager->persist($movie);
        $manager->persist($movieLabel);
        $manager->flush();

        $this->addReference('test-movie', $movie);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
