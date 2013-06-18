<?php
// /src/g5/MovieBundle/Tests/Controller/MovieControllerTest.php

namespace g5\MovieBundle\Tests\Controller;

use g5\AccountBundle\Tests\AccountAwareWebTestCase;

class MovieControllerTest extends AccountAwareWebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/movie/add/550');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $content = $client->getResponse()->getContent();
        $content = json_decode($content);

        $this->assertEquals(550, $content);
    }

    public function testSearch()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/movie/search');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter("button[id=btnSearch]")->count());

        $form = $crawler->selectButton('btnSearch')->form();

        $form['g5_movie_search[search]'] = "";
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isNotFound());

        $form['g5_movie_search[search]'] = "Fight Club";
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Fight Club")')->count());
    }

    // public function testLoadmeta()
    // {
    //     $this->login();

    //     $crawler = $this->client->request('POST', '/movie/loadmeta/550');

    //     $this->assertTrue($this->client->getResponse()->isSuccessful());

    //     $content = $this->client->getResponse()->getContent();
    //     $content = json_decode($content);

    //     $this->assertTrue($content instanceof \stdClass);
    // }

    // private function login()
    // {
    //     $this->client = static::createClient();
    //     $session = $this->client->getContainer()->get('session');

    //     $firewall = 'main';
    //     $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));

    //     $session->set('_security_'.$firewall, serialize($token));
    //     $session->save();

    //     $cookie = new Cookie($session->getName(), $session->getId());
    //     $this->client->getCookieJar()->set($cookie);
    // }
}
