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

    public function testPutMovieActionAddNewLabel()
    {
        $token = uniqid();
        $newLabelName = uniqid();
        $this->loginOAuth('test@example.com', $token);

        $mm = $this->get('g5_movie.movie_manager');
        $movie = $mm->repository->findOneBy(array());

        $labelsExists = array();
        foreach ($movie->getLabels() as $label) {
            $labelsExists[] = array(
                'name' => $label->getName()
            );
        }

        $url = '/api/movies/'.$movie->getId().'.json?access_token='.$token;

        $this->client->request('PUT', $url, array(
            'labels' => $labelsExists
        ));

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        $labels = $content['labels'];

        $expected = array_map(function($label) {
            return $label->getName();
        }, $movie->getLabels()->toArray());

        $compare = array_map(function($label) {
            return $label['name'];
        }, $labels);

        $this->assertEquals($expected, $compare);

        return $movie->getId();
    }

    /**
     * @depends testPutMovieActionAddNewLabel
     */
    public function testPutMovieActionRemoveLabel($id)
    {
        $token = uniqid();
        $this->loginOAuth('test@example.com', $token);

        $mm = $this->get('g5_movie.movie_manager');
        $movie = $mm->repository->find($id);

        $url = '/api/movies/'.$movie->getId().'.json?access_token='.$token;

        $this->client->request('PUT', $url, array(
            'labels' => array()
        ));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($content['labels'], array());

        return $movie->getId();
    }

    /**
     * @depends testPutMovieActionRemoveLabel
     */
    public function testPutMovieActionLinkLabel($id)
    {
        $token = uniqid();
        $this->loginOAuth('test@example.com', $token);

        $mm = $this->get('g5_movie.movie_manager');
        $movie = $mm->repository->find($id);

        $url = '/api/movies/'.$movie->getId().'.json?access_token='.$token;

        $this->client->request('PUT', $url, array(
            'labels' => array(array('name' => 'Horror'))
        ));

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($content['labels'][0]['name'], 'Horror');
    }
}
