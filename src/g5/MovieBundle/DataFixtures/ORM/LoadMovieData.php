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
        $fh = fopen(dirname(__DIR__).'/../../../../app/Resources/meta/TestData/movies.csv', 'r');
        $user = $this->getReference('test-user');

        while (($data = fgetcsv($fh)) !== false) {
            $movie = new Movie();
            $movie->setTmdbId($data[1]);
            $movie->setTitle($data[2]);
            $movie->setReleaseDate(new \DateTime($data[3]));
            $movie->setCoverUrl($data[4]);
            $movie->setOverview($data[5]);
            $movie->setUser($user);

            $manager->persist($movie);
        }

        $manager->flush();

        // $movie = new Movie();
        // $movie->setTmdbId(550);
        // $movie->setTitle('Fight Club');
        // $movie->setOverview(file_get_contents(dirname(__DIR__).'/../../../../app/Resources/meta/TestData/overview_fightclub.txt'));
        // $movie->setReleaseDate(new \DateTime('1999-10-14'));
        // $movie->setCoverUrl('/8uO0gUM8aNqYLs1OsTBQiXu0fEv.jpg');
        // $movie->setUser($this->getReference('test-user'));

        // $movie->addLabel($this->getReference('label-horror'));
        // $manager->persist($movie);
        // $manager->flush();

        // $this->addReference('test-movie', $movie);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
