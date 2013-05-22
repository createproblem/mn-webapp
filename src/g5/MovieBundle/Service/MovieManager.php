<?php
// /src/g5/MovieBundle/Service/MovieManager.php

namespace g5\MovieBundle\Service;

use g5\MovieBundle\Tmdb;
use g5\MovieBundle\Entity\Movie;

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
        // load data from tmdb
        $res = $this->tmdb->getMovieData($tmdbId);

        if (isset($res->status_code)) {
           // error handling here
        }
        $movie = new Movie();
        $movie->setTmdbid($tmdbId);
        $movie->setOverview($res->{Tmdb::DATA_OVERVIEW});
        $movie->setTitle($res->{Tmdb::DATA_TITLE});
        $movie->setCoverUrl($res->{Tmdb::DATA_COVER_URL});
        $movie->setReleaseDate(new \DateTime($res->release_date));

        return $movie;
    }
}
