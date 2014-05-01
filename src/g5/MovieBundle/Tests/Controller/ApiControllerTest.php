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
    protected $fixtureDir;


    public function setUp()
    {
        parent::setUp();

        $this->fixtureDir = $this->container->get('kernel')->getRootDir().'/../src/g5/TmdbBundle/Resources/fixtures';
    }

    public function testLoadAdditionalDataAction()
    {
        $this->login($this->client);
        // $body = file_get_contents($this->fixtureDir.'/response/movie.json');
        $body = '{}';

        $tmdbApi = $this->helper->getTmdbApi($body);
        $this->client->getContainer()->set('g5_tmdb.api.default', $tmdbApi);

        $this->client->request(
            'GET',
            '/movie/api2/movies/550/tmdb',
            array('tmdbId' => 550),
            array(),
            array('HTTP_X-Requested-With' => 'XMLHttpRequest')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    // public function testLoadAdditionalDataActionWrongRequest()
    // {
    //     $this->login($this->client);

    //     $this->client->request(
    //         'GET',
    //         '/movie/api/load_additional_data',
    //         array('tmdbId' => 550)
    //     );

    //     $this->assertFalse($this->client->getResponse()->isSuccessful());
    // }
}
