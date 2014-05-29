<?php

/*
* This file is part of the portal-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\OAuthServerBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class AuthorizeControllerTest extends \g5WebTestCase
{
    public function testAuthorizeAction()
    {
        $this->loginOAuth('test@example.com', uniqid());

        $cm = $this->get('fos_oauth_server.client_manager.default');
        $oAuthClient = $cm->findClientBy(array('name' => 'TestClient'));

        $crawler = $this->client->request('GET', '/oauth/v2/auth', array(
            'client_id' => $oAuthClient->getPublicId(),
            'response_type' => 'code',
            'redirect_uri' => 'http://localhost:8000'
        ));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testAuthorizeActionWithoutClientId()
    {
        $this->loginOAuth('test@example.com', uniqid());

        $crawler = $this->client->request('GET', '/oauth/v2/auth');

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Client id parameter  is missing.")')->count());
    }

    public function testAuthorizeActionWithWrongClientId()
    {
        $this->loginOAuth('test@example.com', uniqid());

        $crawler = $this->client->request('GET', '/oauth/v2/auth', array(
            'client_id' => '123_sdjf08789'
        ));

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Client 123_sdjf08789 is not found.")')->count());
    }

    public function testAuthorizeActionWithFormError()
    {
        $this->loginOAuth('test@example.com', uniqid());

        $cm = $this->get('fos_oauth_server.client_manager.default');
        $oAuthClient = $cm->findClientBy(array('name' => 'TestClient'));

        $crawler = $this->client->request('GET', '/oauth/v2/auth', array(
            'client_id' => $oAuthClient->getPublicId(),
        ));

        $form = $crawler->selectButton('Authorize')->form();
        $form['g5_oauth_server_authorize[allowAccess]']->tick();

        $this->client->submit($form);

        $expected = '{"error":"redirect_uri_mismatch","error_description":"No redirect URL was supplied and more than one is registered."}';

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }
}
