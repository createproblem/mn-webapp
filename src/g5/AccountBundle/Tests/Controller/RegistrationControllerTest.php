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
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/account/register/');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = 'test1';
        $form['fos_user_registration_form[email]'] = 'test1'.'@example.org';
        $form['fos_user_registration_form[plainPassword][first]'] = 'test';
        $form['fos_user_registration_form[plainPassword][second]'] = 'test';
        // $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("The user has been created successfully")')->count() > 0);

        $this->deleteUser($client, 'test1');
    }
}

