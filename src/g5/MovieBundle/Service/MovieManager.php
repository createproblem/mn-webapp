<?php
// /src/g5/MovieBundle/Service/MovieManager.php

namespace g5\MovieBundle\Service;

use g5\MovieBundle\Entity\Movie;

class MovieManager
{
    private $tmdbApi;

    public function __construct($tmdbApi)
    {
        $this->tmdbApi = $tmdbApi;
    }

    /**
     * Generates a Movie Object from a tmdbId
     *
     * @param  int $tmdbId
     *
     * @return Movie
     */
    public function createMovieFromTmdb($tmdbId)
    {
        // load data from tmdb
        $res = $this->tmdbApi->getMovie($tmdbId);

        $movie = new Movie();
        $movie->setTmdbid($tmdbId);
        $movie->setOverview($res['overview']);
        $movie->setTitle($res['original_title']);
        $movie->setCoverUrl($res['poster_path']);
        $movie->setReleaseDate(new \DateTime($res['release_date']));

        return $movie;
    }
}
