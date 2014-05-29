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
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use g5\OAuthServerBundle\Document\AccessToken;

use g5\MovieBundle\Entity\Movie;

require_once dirname(__DIR__).'/app/TestHelper.php';

abstract class g5WebTestCase extends WebTestCase
{
    /**
     * @var TestHelper
     */
    protected $helper;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->helper = new TestHelper($this->client->getContainer());
        $this->container = $this->client->getContainer();
    }

    protected function login(&$client)
    {
        $this->loginAs($client, 'test');
    }

    protected function loginAs(&$client, $username)
    {
        $container = $client->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $user = $this->helper->loadUser($username);

        $session = $client->getContainer()->get('session');
        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_ADMIN'));

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    protected function loginOAuth($email, $newToken)
    {
        $om = $this->get('doctrine_mongodb')->getManager();
        $um = $this->get('fos_user.user_manager');
        $cm = $this->get('fos_oauth_server.client_manager.default');

        $oAuthClient = $cm->findClientBy(array('name' => 'TestClient'));
        $user = $um->findUserByEmail($email);

        $session = $this->get('session');
        $firewall = 'oauth_authorize';

        $token = new OAuthToken(array('ROLE_ADMIN'));
        $token->setAuthenticated(true);
        $token->setToken($newToken);

        $accessToken = new AccessToken();
        $accessToken->setUser($user);
        $accessToken->setClient($oAuthClient);
        $accessToken->setToken($newToken);

        $om->persist($accessToken);
        $om->flush();

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
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

    protected function get($identifier)
    {
        return $this->client->getContainer()->get($identifier);
    }
}
