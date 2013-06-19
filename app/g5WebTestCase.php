<?php

/*
* This file is part of the mn-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

abstract class g5WebTestCase extends WebTestCase
{
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
}
