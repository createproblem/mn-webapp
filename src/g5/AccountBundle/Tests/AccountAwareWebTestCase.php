<?php

namespace g5\AccountBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AccountAwareWebTestCase extends WebTestCase
{
    protected function addUser()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/account/register/');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = 'test';
        $form['fos_user_registration_form[email]'] = 'test'.'@example.org';
        $form['fos_user_registration_form[plainPassword][first]'] = 'test';
        $form['fos_user_registration_form[plainPassword][second]'] = 'test';
        // $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("The user has been created successfully")')->count() > 0);
    }

    protected function delUser()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $userRepo = $em->getRepository('g5AccountBundle:User');

        $user = $userRepo->findOneBy(array('username' => 'test'));
        $em->remove($user);
        $em->flush();
    }

    protected function login(&$client)
    {
        $userRepo = $client->getContainer()->get('doctrine')->getManager()->getRepository('g5AccountBundle:User');
        $user = $userRepo->findOneBy(array('username' => 'test'));

        $session = $client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_ADMIN'));

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
