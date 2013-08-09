<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Util;

use g5\MovieBundle\Entity\Movie;

class MovieManager
{
    /**
     * @var integer
     */
    private $tmdbApi;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @param  integer $tmdbApi
     *
     * @param  $doctrine
     */
    public function __construct($tmdbApi, $doctrine)
    {
        $this->tmdbApi = $tmdbApi;
        $this->em = $doctrine->getManager();
        $this->repository = $this->em->getRepository('g5MovieBundle:Movie');
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

    /**
     * @return Movie
     */
    public function createMovie()
    {
        return new Movie();
    }

    /**
     * @param  array    $criteria
     * @param  array    $orderBy
     * @param  integer  $limit
     * @param  integer  $offset
     *
     * @return array
     */
    public function findMoviesBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param  integer                       $id
     * @param  \g5\AccountBundle\Entity\User $user
     *
     * @return Movie|null
     */
    public function loadMovieById($id, \g5\AccountBundle\Entity\User $user = null)
    {
        $criteria = array('id' => $id);
        if ($user !== null) {
            $filter['user'] = $user;
        }

        $movie = $this->repository->findOneBy($criteria);

        // exception
        return $movie;
    }

    public function findMoviesByLabel(\g5\MovieBundle\Entity\Label $label, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findByLabel($label, $orderBy, $limit, $offset);
    }

    /**
     * @param  \g5\MovieBundle\Entity\Movie $movie
     *
     */
    public function updateMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $this->em->persist($movie);
        $this->em->flush();
    }

    /**
     * @param  \g5\MovieBundle\Entity\Movie $movie
     *
     */
    public function removeMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $this->em->remove($movie);
        $this->em->flush();
    }
}
