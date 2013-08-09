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

class LabelControllerTest extends \g5WebTestCase
{
    private $client;
    private $mm;
    private $lm;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->mm = $this->client->getContainer()->get('g5_movie.movie_manager');
        $this->lm = $this->client->getContainer()->get('g5_movie.label_manager');
    }

    public function testIndexAction()
    {
        $this->login($this->client);

        $label = $this->createTestLabel();
        $movie = $this->createTestMovie();
        $movie->addLabel($label);
        $this->mm->updateMovie($movie);

        $tmdbMock = $this->getTmdbMock();
        $tmdbMock->expects($this->any())
            ->method('getImageUrl')
            ->with('w185')
            ->will($this->returnValue(''))
        ;

        static::$kernel->setKernelModifier(function($kernel) use ($tmdbMock) {
            $kernel->getContainer()->set('g5_tools.tmdb.api', $tmdbMock);
        });

        $crawler = $this->client->request('GET', '/movie/label/'.$label->getNameNorm());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h4:contains("'.$movie->getTitle().'")')->count());

        $this->mm->removeMovie($movie);
        $this->lm->removeLabel($label);
    }
}
