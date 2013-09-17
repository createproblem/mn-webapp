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
use g5\MovieBundle\Entity\Label;
use g5\MovieBundle\Entity\MovieLabel;
use g5\AccountBundle\Entity\User;

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
        $movie->setPosterPath($res['poster_path']);
        $movie->setBackdropPath($res['backdrop_path']);
        $movie->setReleaseDate(new \DateTime($res['release_date']));
        $movie->setCreatedAt(new \DateTime());

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
     * Returns the movie with the given id. If the user is not accoiated
     * with the user id, the movie cannot be loaded.
     *
     * @param  int      $id
     * @param  User     $user
     *
     * @return Movie|null
     */
    public function loadMovieById($id, User $user = null)
    {
        $criteria = array('id' => $id);
        if (null !== $user) {
            $criteria['user'] = $user;
        }

        $movie = $this->repository->findOneBy($criteria);

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

    /**
     * @param  User     $user
     * @param  int      $limit
     *
     * @return array
     */
    public function loadLatestMovies(\g5\AccountBundle\Entity\User $user, $limit = 12)
    {
        return $this->findMoviesBy(array('user' => $user), array('created_at' => 'DESC'), $limit);
    }

    /**
     * @param  g5AccountBundleEntityUser $user
     * @param  integer                   $limit
     *
     * @return array
     */
    public function loadRandomMovies(\g5\AccountBundle\Entity\User $user, $limit = 5)
    {
        $ids = $this->repository->findMovieIdsByUser($user);

        $limit = (count($ids) > $limit ? $limit : count($ids));

        $randomIdKeys = array_rand($ids, $limit);

        if (!is_array($randomIdKeys)) {
            $randomIdKeys = array($randomIdKeys);
        }
        $randomIds = array();

        foreach ($randomIdKeys as $id) {
            array_push($randomIds, $ids[$id]);
        }


        $movies = $this->repository->findMoviesByIds($randomIds);

        return $movies;
    }

    public function findMoviesWithoutLabel(\g5\AccountBundle\Entity\User $user, $limit = null, $offset = null)
    {
        return $this->repository->findBy(array('user' => $user, 'label_count' => 0), array(), $limit, $offset);
    }

    /**
     * Returns all favorite movies or all movie that are no favorite
     *
     * @param  User    $user
     * @param  boolean $favorite
     *
     * @return array
     */
    public function loadMoviesByFavorite(User $user, $favorite = true, $limit = null, $offset = null)
    {
        $criteria = array(
            'user' => $user,
            'favorite' => $favorite,
        );

        return $this->repository->findBy($criteria, array('title' => 'ASC'), $limit, $offset);
    }
}
