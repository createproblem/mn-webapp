<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

final class TestHelper
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function createTestMovie($save = true)
    {
        $mm = $this->container->get('g5_movie.movie_manager');
        $um = $this->container->get('fos_user.user_manager');

        $movie = $mm->createMovie();
        $user = $um->findUserByUsername('test');

        $movie->setTmdbId(uniqid());
        $movie->setTitle('Power Rangers');
        $movie->setReleaseDate(new DateTime(1995));
        $movie->setOverview(file_get_contents($this->getTestDataDir().'/overview_9070.txt'));
        $movie->setPosterPath('/A3ijhraMN0tvpDnPoyVP7NulkSr.jpg');
        $movie->setBackdropPath('/u5jVc4Ks48ldQ4hvHos0JxCDhg4.jpg');
        $movie->setCreatedAt(new \DateTime());
        $movie->setUser($user);

        $mm->updateMovie($movie);

        return $movie;
    }

    public function getTmdbApi($returnValue)
    {
        $api = $this->container->get('g5_tmdb.api.default');
        $api->addSubscriber($this->getMockResponsePlugin($returnValue, 200));

        return $api;
    }

    private function getMockResponsePlugin($body, $status)
    {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $response = new \Guzzle\Http\Message\Response($status);
        $response->setBody($body);
        $response->setInfo(array('total_time' => 0.1));

        $plugin->addResponse($response);

        return $plugin;
    }
}
