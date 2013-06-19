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

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }
}
