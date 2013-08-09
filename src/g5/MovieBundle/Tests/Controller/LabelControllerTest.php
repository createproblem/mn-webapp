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

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->mm = $this->client->getContainer()->get('g5_movie.movie_manager');
    }

    public function testIndexAction()
    {
        $this->login($this->client);

        $label = $this->createTestLabel();
        $movie = $this->createTestMovie();
        $movie->addLabel($label);
        $this->mm->updateMovie($movie);

        $crawler = $this->client->request('GET', '/movie/label/'.$label->getNameNorm());
print($this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h4:contains("'.$movie->getTitle().'")')->count());

        $this->deleteLabel($label);
        $this->deleteMovie($movie);
    }
}
