<?php
// /src/g5/MovieBundle/Tests/Controller/MovieControllerTest.php

namespace g5\MovieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/movie/add');

        $buttonCrawlerNode = $crawler->selectButton('Add Movie');

        $form = $buttonCrawlerNode->form();
    }
}
