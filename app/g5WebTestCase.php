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

    /**
     * @return g5\AccountBundle\Entity\User
     */
    protected function loadTestUser()
    {
        $um = static::$kernel->getContainer()->get('fos_user.user_manager');
        $user = $um->findUserByUsername('test');

        return $user;
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

        return $user;
    }

    /**
     * @param  boolean $save
     *
     * @return \g5\MovieBundle\Entity\Movie
     */
    protected function createTestMovie($save = true)
    {
        $mm = static::$kernel->getContainer()->get('g5_movie.movie_manager');
        $um = static::$kernel->getContainer()->get('fos_user.user_manager');

        $user = $this->loadUser($um, 'test');
        $movie = $mm->createMovie();

        $movie->setTmdbId(9070);
        $movie->setTitle('Power Rangers');
        $movie->setReleaseDate(new DateTime(1995));
        $movie->setOverview(file_get_contents($this->getTestDataDir().'/overview_9070.txt'));
        $movie->setCoverUrl('/A3ijhraMN0tvpDnPoyVP7NulkSr.jpg');
        $movie->setUser($user);

        if (true === $save) {
            if (true === $save) {
                try {
                    $mm->updateMovie($movie);
                } catch (Doctrine\DBAL\DBALException $e) {
                    $this->fail($e->getMessage());
                }
            }
        }

        return $movie;
    }

    protected function deleteMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $mm = static::$kernel->getContainer()->get('g5_movie.movie_manager');
        $mm->removeMovie($movie);
    }

    /**
     * @param  boolean $save
     *
     * @return \g5\MovieBundle\Entity\Label
     */
    protected function createTestLabel($save = true)
    {
        $lm = static::$kernel->getContainer()->get('g5_movie.label_manager');
        $um = static::$kernel->getContainer()->get('fos_user.user_manager');

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
        $lm = static::$kernel->getContainer()->get('g5_movie.label_manager');
        $lm->removeLabel($label);
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

    protected function getMovieManagerMock()
    {
        $movieManagerMock = $this->getMockBuilder('g5\MovieBundle\Util\MovieManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $movieManagerMock;
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
}
