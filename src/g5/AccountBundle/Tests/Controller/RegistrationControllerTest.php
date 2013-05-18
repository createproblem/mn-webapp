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

        $buttonCrawlerNode = $crawler->selectButton('registration.submit');

        $form = $buttonCrawlerNode->form();

        $form['fos_user_registration_form[username]'] = $time;
        $form['fos_user_registration_form[email]'] = $time.'@example.org';
        $form['fos_user_registration_form[plainPassword][password]'] = $time;
        $form['fos_user_registration_form[plainPassword][confirm_password]'] = $time;
        $form['fos_user_registration_form[termsOfService]']->tick();

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("registration.flash.user_created")')->count() > 0);
    }
}

