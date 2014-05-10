<?php

/*
* This file is part of the mn-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\AccountBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class RegistrationControllerTest extends \g5WebTestCase
{
    public function testRegister()
    {
        $um = $this->get('fos_user.user_manager');

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/account/register/');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = 'test1';
        $form['fos_user_registration_form[email]'] = 'test1'.'@example.org';
        $form['fos_user_registration_form[plainPassword][first]'] = 'test';
        $form['fos_user_registration_form[plainPassword][second]'] = 'test';
        // $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Congrats test1, your account is now activated.")')->count() > 0);

        $user = $this->helper->loadUser('test1');
        $um->deleteUser($user);
    }
}

