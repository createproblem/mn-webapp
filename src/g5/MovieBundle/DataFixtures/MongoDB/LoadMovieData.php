<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\AccountBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use g5\MovieBundle\Document\Movie;

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
        $dataFiles = array(
            dirname(__DIR__).'/../../../../app/Resources/meta/TestData/1562.json',
            dirname(__DIR__).'/../../../../app/Resources/meta/TestData/26587.json',
            dirname(__DIR__).'/../../../../app/Resources/meta/TestData/277.json',
            dirname(__DIR__).'/../../../../app/Resources/meta/TestData/550.json',
        );

        $user = $this->getReference('test-user');

        $labels = array('label-Action', 'label-Horror', 'label-Top-Hits');
        $label = $this->getReference($labels[array_rand($labels, 1)]);

        foreach ($dataFiles as $file) {
            $data = file_get_contents($file);
            $movie = $this->createMovieFromData(json_decode($data, true));
            $movie->setUser($user);
            $movie->addLabel($label);
            $manager->persist($movie);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }

    private function createMovieFromData($data)
    {
        $movie = new Movie();

        $movie->setTmdbId($data['id']);
        $movie->setOverview($data['overview']);
        $movie->setTitle($data['original_title']);
        $movie->setPosterPath($data['poster_path']);
        $movie->setBackdropPath($data['backdrop_path']);
        $movie->setReleaseDate(new \DateTime($data['release_date']));
        $movie->setCreatedAt(new \DateTime());

        return $movie;
    }
}
