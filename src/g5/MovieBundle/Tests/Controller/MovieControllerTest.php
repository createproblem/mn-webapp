<?php
// /src/g5/MovieBundle/Tests/Controller/MovieControllerTest.php

/*
* This file is part of the mn-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class MovieControllerTest extends \g5WebTestCase
{
    public function setUp()
    {
        $this->createUser(static::createClient(), 'test');
    }

    public function testIndex()
    {
        $client = static::createClient();
        $this->loginAs($client, 'test');

        $crawler = $client->request('GET', '/movie/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testAdd()
    {
        $client = static::createClient();
        $this->loginAs($client, 'test');

        $crawler = $client->request('GET', '/movie/add/550');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $content = $client->getResponse()->getContent();
        $content = json_decode($content);

        $this->assertEquals(550, $content);
    }

    public function testAddFail()
    {
        $client = static::createClient();
        $this->loginAs($client, 'test');

        $crawler = $client->request('GET', '/movie/add/550');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $content = $client->getResponse()->getContent();
        $content = json_decode($content);

        $this->assertEquals(550, $content);

        $crawler = $client->request('GET', '/movie/add/550');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $content = $client->getResponse()->getContent();
        $content = json_decode($content);
        $this->assertEquals('This value is already used.', $content[0]);
    }

    public function testSearch()
    {
        $client = static::createClient();
        $this->loginAs($client, 'test');

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

    public function testLoadmeta()
    {
        $client = static::createClient();
        $this->loginAs($client, 'test');

        $crawler = $client->request('POST', '/movie/loadmeta/550');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $content = $client->getResponse()->getContent();
        $content = json_decode($content);

        $this->assertTrue($content instanceof \stdClass);
    }

    public function tearDown()
    {
        $this->deleteUser(static::createClient(), 'test');
    }
}
