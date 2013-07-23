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

class ApiControllerTest extends \g5WebTestCase
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

    public function testLabelNewAction()
    {
        $this->login($this->client);

        $this->client->request('GET', '/movie/api/label/new');

        $this->assertFalse($this->client->getResponse()->isSuccessful());

        $crawler = $this->client->request('GET', '/movie/api/label/new',
            array(),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testLabelFindAction()
    {
        $this->login($this->client);

        $this->client->request('GET', '/movie/api/label/find',
            array('query' => 'horror'),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testLabelAddAction()
    {
        $this->login($this->client);

        // pre setup
        $movie = $this->createTestMovie();

        $expected = 'OK';

        $token = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('g5_movie_label');

        // Session Mock failure workaround
        $session = static::$kernel->getContainer()->get('session');
        $session->save();

        $this->client->request('POST', '/movie/api/label/add',
            array(
                'label' => array(
                    'name' => 'test1',
                    '_token' => $token,
                    'movie_id' => $movie->getId(),
                ),
            ),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $compare = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($expected, $compare['status']);

        $this->deleteMovie($movie);
    }

    public function testAddActionError()
    {
        $this->login($this->client);
        $movie = $this->createTestMovie();
        $expected = "ERROR: The CSRF token is invalid. Please try to resubmit the form.\nname:\n    No errors\nmovie_id:\n    No errors\n";

        $this->client->request('POST', '/movie/api/label/add',
            array(
                'label' => array(
                    'name' => 'test1',
                    'movie_id' => $movie->getId(),
                ),
            ),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($expected, json_decode($this->client->getResponse()->getContent(), true));

        $this->deleteMovie($movie);
    }

    public function testUnlinkAction()
    {
        $this->login($this->client);
        $label = $this->createTestLabel();
        $movie = $this->createTestMovie();
        $expected = array('status' => 'OK');
        $movie->addLabel($label);

        $this->mm->updateMovie($movie);


        $this->client->request('GET', '/movie/api/unlink',
            array(
                'labelId' => $label->getId(),
                'movieId' => $movie->getId(),
            ),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($expected, json_decode($this->client->getResponse()->getContent(), true));

        $this->deleteMovie($movie);
    }

    public function testUnlinkActionError()
    {
        $this->login($this->client);
        $label = $this->createTestLabel();
        $movie = $this->createTestMovie();
        $expected = array('status' => 'ERROR');
        $movie->addLabel($label);

        $this->mm->updateMovie($movie);


        $this->client->request('GET', '/movie/api/unlink',
            array(
                'labelId' => $label->getId(),
                'movieId' => 9999,
            ),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($expected, json_decode($this->client->getResponse()->getContent(), true));

        $this->deleteMovie($movie);
    }
}
