<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Finder\Finder;

use g5\MovieBundle\Entity\Movie;

abstract class g5WebTestCase extends WebTestCase
{
    private $classesLoaded = false;
    private $em = null;
    private $container = null;

    protected function login(&$client)
    {
        $this->loginAs($client, 'test');
    }

    protected function loginAs(&$client, $username)
    {
        $container = $client->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $user = $this->loadUser($userManager, $username);

        $session = $client->getContainer()->get('session');
        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_ADMIN'));

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    private function loadUser($userManager, $username)
    {
        return $userManager->findUserByUsername($username);
    }

    protected function createUser($client, $username)
    {
        $container = $client->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $user->setUsername($username);
        $user->setEmail($username.'@example.org');
        $user->setPassword('test');

        try {
            $userManager->updateUser($user);
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->fail($e->getMessage());
            $this->deleteUser($client, $username);
        }
    }

    protected function deleteUser($client, $username)
    {
        $container = $client->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $user = $userManager->findUserByUsername($username);

        $userManager->deleteUser($user);
    }

    protected function getTmdbMock()
    {
        $tmdbMock = $this->getMockBuilder('g5\ToolsBundle\Tmdb\TmdbApi')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $tmdbMock;
    }

    /**
     * {@inheritDoc}
     */
    protected static function getKernelClass()
    {
        $dir = isset($_SERVER['KERNEL_DIR']) ? $_SERVER['KERNEL_DIR'] : static::getPhpUnitXmlDir();

        $finder = new Finder();
        $finder->name('*TestKernel.php')->depth(0)->in($dir);
        $results = iterator_to_array($finder);
        if (!count($results)) {
            throw new \RuntimeException('Either set KERNEL_DIR in your phpunit.xml according to http://symfony.com/doc/current/book/testing.html#your-first-functional-test or override the WebTestCase::createKernel() method.');
        }

        $file = current($results);
        $class = $file->getBasename('.php');

        require_once $file;

        return $class;
    }

    protected function getTestDataDir()
    {
        return static::$kernel->getRootDir().'/Resources/meta/TestData';
    }

    protected function createTestMovieEventHorizon()
    {
        $movie = new Movie();
        $movie->setTitle('Event Horizon');
        $movie->setCoverUrl('/vo02iJLsem3VCJ2TNvSzRiJMpAE.jpg');
        $movie->setReleaseDate(new \DateTime('1999'));
        $movie->setOverview(file_get_contents($this->getTestDataDir().'/overview_eventhorizon.txt'));
        $movie->setTmdbId(8413);

        return $movie;
    }

    protected function delTestMovieEventHorizon()
    {
        $this->loadClasses();

        $user = $this->loadUser($this->container->get('fos_user.user_manager'), 'test');

        $movieRepo = $this->em->getRepository('g5MovieBundle:Movie');
        $movie = $movieRepo->findOneBy(array('user' => $user, 'tmdb_id' => 8413));

        $this->em->remove($movie);
        $this->em->flush();
    }

    private function loadClasses()
    {
        if ($this->classesLoaded) {
            return;
        }

        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        $this->classesLoaded = true;
    }
}
