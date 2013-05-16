<?php

namespace g5\AccountBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/register/');

        $buttonCrawlerNode = $crawler->selectButton('registration.submit');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = 'Test';
        $form['fos_user_registration_form[email]'] = 'test@example.org';
        $form['fos_user_registration_form[plainPassword][password]'] = 'test';
        $form['fos_user_registration_form[plainPassword][confirm_password]'] = 'test';
        $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("registration.flash.user_created")')->count() > 0);
    }
}

