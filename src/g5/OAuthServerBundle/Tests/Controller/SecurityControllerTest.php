<?php


namespace g5\OAuthServerBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class SecurityControllerTest extends \g5WebTestCase
{
    public function testLoginAction()
    {
        $crawler = $this->client->request('GET', '/oauth/v2/auth_login');
        // $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('form')->count());

        $form = $crawler->selectButton('Log In')->form();
        $form['_username'] = 'test';
        $form['_password'] = 'test';

        $this->client->submit($form);
    }
}
