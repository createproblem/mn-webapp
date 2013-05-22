<?php
// /src/g5/MovieBundle/Service/MovieManager.php

namespace g5\MovieBundle\Service;

use g5\MovieBundle\Tmdb;
use g5\MovieBundle\Document\Movie;

class MovieManager
{
    private $tmdb;

    public function __construct($tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Generates a Movie Object from a tmdbId
     *
     * @param  int $tmdbId
     *
     * @return Movie
     */
    public function createMovie($tmdbId)
    {
        $movie = new Movie();
        $movie->setTmdbid($tmdbId);

        return $movie;
    }
}
