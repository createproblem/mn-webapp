<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class MovieApiControllerTest extends \g5WebTestCase
{
    public function testGetMoviesAction()
    {
        $token = uniqid();
        $this->loginOAuth('test@example.com', $token);

        $this->client->request('GET', '/api/movies.json', array('access_token' => $token));

        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);

        $this->assertTrue(is_array($content));
        $this->assertGreaterThan(0, count($content));
    }

    public function testPostMovieAction()
    {
        $this->markTestSkipped('Not yet implemented');
    }
}
