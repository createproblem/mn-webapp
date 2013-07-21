<?php
// /src/g5/MovieBundle/Service/MovieManager.php

namespace g5\MovieBundle\Service;

use g5\MovieBundle\Entity\Movie;

class MovieManager
{
    private $tmdbApi;
    private $em;
    private $movieRepo;

    public function __construct($tmdbApi, $doctrine)
    {
        $this->tmdbApi = $tmdbApi;
        $this->em = $doctrine->getManager();
        $this->movieRepo = $this->em->getRepository('g5MovieBundle:Movie');
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

    public function findById($id)
    {
        return $this->movieRepo->findOneById($id);
    }

    public function findeMovieById($id, \g5\AccountBundle\Entity\User $user = null)
    {
        return $this->movieRepo->findOneBy(array('id' => $id, 'user' => $user));
    }

    /**
     * @param  \g5\AccountBundle\Entity\User $user
     * @param  integer                       $limit
     * @param  integer                       $offset
     *
     * @return array
     */
    public function findMoviesByUser(\g5\AccountBundle\Entity\User $user, $limit = null, $offset = null)
    {
        $movies = $this->movieRepo->findBy(
            array(
                'user' => $user,
            ),
            array(
                'title' => 'ASC',
            ),
            $limit,
            $offset
        );

        return $movies;
    }

    public function getMovieCountByUser(\g5\AccountBundle\Entity\User $user)
    {
        return $this->movieRepo->getMovieCountByUser($user);
    }

    public function updateMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        if (null == $movie->getId()) {
            $this->em->persist($movie);
        }

        $this->em->flush();
    }
}
