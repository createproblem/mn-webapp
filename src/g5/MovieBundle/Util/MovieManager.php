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

use Symfony\Component\Security\Core\User\UserInterface as User;
use g5\MovieBundle\Entity\Movie;

class MovieManager
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    public $repository;

    /**
     * Constructor.
     *
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Doctrine\ORM\EntityManager    $em
     */
    public function __construct($repository, $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Generates a Movie Object from tmdb data
     *
     * @param  Guzzle\Service\Resource\Model $data
     *
     * @return Movie
     */
    public function createMovieFromTmdb(\Guzzle\Service\Resource\Model $data)
    {
        $movie = new Movie();
        $movie->setTmdbid($data['id']);
        $movie->setOverview($data['overview']);
        $movie->setTitle($data['original_title']);
        $movie->setPosterPath($data['poster_path']);
        $movie->setBackdropPath($data['backdrop_path']);
        $movie->setReleaseDate(new \DateTime($data['release_date']));
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
     * @param  \g5\MovieBundle\Entity\Movie $movie
     *
     */
    public function updateMovie(Movie $movie)
    {
        $this->em->persist($movie);
        $this->em->flush();
    }

    /**
     * @param  \g5\MovieBundle\Entity\Movie $movie
     *
     */
    public function removeMovie(Movie $movie)
    {
        $this->em->remove($movie);
        $this->em->flush();
    }
}
