<?php

namespace g5\AccountBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $time = time();
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/account/register/');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = $time;
        $form['fos_user_registration_form[email]'] = $time.'@example.org';
        $form['fos_user_registration_form[plainPassword][first]'] = $time;
        $form['fos_user_registration_form[plainPassword][second]'] = $time;
        // $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("The user has been created successfully")')->count() > 0);
    }
}

