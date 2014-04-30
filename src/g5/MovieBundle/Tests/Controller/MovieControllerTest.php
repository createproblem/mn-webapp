<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class MovieControllerTest extends \g5WebTestCase
{
    private $mm;

    public function setUp()
    {
        parent::setUp();

        $this->mm = $this->client->getContainer()->get('g5_movie.movie_manager');
    }

    public function testNewActionWithMethodGet()
    {
        $this->login($this->client);

        $crawler = $this->client->request('GET', '/movie/new');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testNewActionWithMethodPost()
    {
        $this->login($this->client);
        $crawler = $this->client->request('GET', '/movie/new');

        $form = $crawler->selectButton('Search')->form();
        $form['g5_movie_search[search]'] = 'Fight Club';

        $tmdbApi = $this->helper->getTmdbApi('{}');

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbApi) {
            $kernel->getContainer()->set('g5_tmdb.api.default', $tmdbApi);
        });

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testIndexAction()
    {
        // $this->login($this->client);
        // $movie = $this->createTestMovie();

        // $tmdbMock = $this->getTmdbMock();
        // $tmdbMock->expects($this->any())
        //     ->method('getImageUrl')
        //     ->will($this->returnValue(''))
        // ;

        // static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
        //     $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        // });

        // $crawler = $this->client->request('GET', '/movie/'.$movie->getId());

        // $this->assertTrue($this->client->getResponse()->isSuccessful());
        // $this->assertEquals(1, $crawler->filter('html:contains("'.$movie->getTitle().'")')->count());

        // // Reset MovieManager
        // $mm = $this->mm;
        // static::$kernel->setKernelModifier(function($kernel) use ($mm) {
        //     $kernel->getContainer()->set('g5_movie.movie_manager', $mm);
        // });
        // static::$kernel->boot();

        // $this->deleteMovie($movie);
    }
}
