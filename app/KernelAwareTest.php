<?php
// /app/KernelAwareTest.php

require_once dirname(__DIR__).'/app/AppKernel.php';

/**
* Test case class helpful with Entity tests requiring the database interaction.
* For regular entity tests it's better to extend standard \PHPUnit_Framework_TestCase instead.
*/
abstract class KernelAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @return null;
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        $this->rootDir = $this->kernel->getRootDir();

        $this->container = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getEntityManager();

        parent::setUp();
    }

    protected function getTestDataDir()
    {
        return $this->kernel->getRootDir().'/Resources/meta/TestData';
    }

    /**
     * @param  boolean $save
     *
     * @return g5\MovieBundle\Entity\Movie
     */
    protected function createTestMovie($save = true)
    {
        $mm = $this->container->get('g5_movie.movie_manager');
        $um = $this->container->get('fos_user.user_manager');

        $movie = $mm->createMovie();
        $user = $um->findUserByUsername('test');

        $movie->setTmdbId(9070);
        $movie->setTitle('Power Rangers');
        $movie->setReleaseDate(new DateTime(1995));
        $movie->setOverview(file_get_contents($this->getTestDataDir().'/overview_9070.txt'));
        $movie->setCoverUrl('/A3ijhraMN0tvpDnPoyVP7NulkSr.jpg');
        $movie->setUser($user);

        if (true === $save) {
            try {
                $mm->updateMovie($movie);
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->fail($e->getMessage());
            }
        }

        return $movie;
    }

    protected function deleteMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $mm = $this->container->get('g5_movie.movie_manager');
        $mm->removeMovie($movie);
    }

    /**
     * @param  boolean $save
     *
     * @return \g5\MovieBundle\Entity\Label
     */
    protected function createTestLabel($save = true)
    {
        $lm = $this->container->get('g5_movie.label_manager');
        $um = $this->container->get('fos_user.user_manager');

        $label = $lm->createLabel();
        $user = $um->findUserByUsername('test');

        $label->setName('Test-Label');
        $label->setNameNorm('test-label');
        $label->setUser($user);

        if (true === $save) {
            try {
                $lm->updateLabel($label);
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->fail($e->getMessage());
            }
        }

        return $label;
    }

    protected function deleteLabel(\g5\MovieBundle\Entity\Label $label)
    {
        $lm = $this->container->get('g5_movie.label_manager');
        $lm->removeLabel($label);
    }

    /**
     * @return g5\AccountBundle\Entity\User
     */
    protected function loadTestUser()
    {
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserByUsername('test');

        return $user;
    }

    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }
}
