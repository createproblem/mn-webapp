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
        $unique = uniqid();

        $movie = $mm->createMovie();
        $movie->setUser($this->loadUser('test'));
        $movie->setTmdbId(uniqid());
        $movie->setTitle('Power Rangers');
        $movie->setReleaseDate(new DateTime(1995));
        $movie->setOverview('Test Overview');
        $movie->setPosterPath('/A3ijhraMN0tvpDnPoyVP7NulkSr.jpg');
        $movie->setBackdropPath('/u5jVc4Ks48ldQ4hvHos0JxCDhg4.jpg');
        $movie->setCreatedAt(new \DateTime());

        if (true === $save) {
            $mm->updateMovie($movie);
        }

        return $movie;
    }

    public function loadUser($username)
    {
        $um = $this->container->get('fos_user.user_manager');
        return $um->findUserByUsername($username);
    }

    public function getTmdbApi($responseStack = array())
    {
        $api = $this->container->get('g5_tmdb.api.default');
        $api->addSubscriber($this->getMockResponsePlugin($responseStack));

        return $api;
    }

    private function getMockResponsePlugin($responseStack)
    {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();

        foreach ($responseStack as $body) {
            $response = new \Guzzle\Http\Message\Response(200);
            $response->setBody($body);
            $response->setInfo(array('total_time' => 0.1));
            $plugin->addResponse($response);
        }

        return $plugin;
    }
}
