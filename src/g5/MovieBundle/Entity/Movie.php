<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Entity;

use g5\MovieBundle\Entity\Model\MovieAbstract as MovieBase;

/**
 * Movie
 */
class Movie extends MovieBase
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $tmdb_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $overview;

    /**
     * @var \DateTime
     */
    private $release_date;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $movieLabels;

    /**
     * @var \g5\AccountBundle\Entity\User
     */
    private $user;

    /**
     * @var string
     */
    private $backdrop_path;

    /**
     * @var string
     */
    private $poster_path;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movieLabels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tmdb_id
     *
     * @param integer $tmdbId
     * @return Movie
     */
    public function setTmdbId($tmdbId)
    {
        $this->tmdb_id = $tmdbId;

        return $this;
    }

    /**
     * Get tmdb_id
     *
     * @return integer
     */
    public function getTmdbId()
    {
        return $this->tmdb_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set overview
     *
     * @param string $overview
     * @return Movie
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * Get overview
     *
     * @return string
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set release_date
     *
     * @param \DateTime $releaseDate
     * @return Movie
     */
    public function setReleaseDate($releaseDate)
    {
        $this->release_date = $releaseDate;

        return $this;
    }

    /**
     * Get release_date
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->release_date;
    }

    /**
     * Add movieLabels
     *
     * @param \g5\MovieBundle\Entity\MovieLabel $movieLabels
     * @return Movie
     */
    public function addMovieLabel(\g5\MovieBundle\Entity\MovieLabel $movieLabels)
    {
        $this->movieLabels[] = $movieLabels;

        return $this;
    }

    /**
     * Remove movieLabels
     *
     * @param \g5\MovieBundle\Entity\MovieLabel $movieLabels
     */
    public function removeMovieLabel(\g5\MovieBundle\Entity\MovieLabel $movieLabels)
    {
        $this->movieLabels->removeElement($movieLabels);
    }

    /**
     * Get movieLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovieLabels()
    {
        return $this->movieLabels;
    }

    /**
     * Set user
     *
     * @param \g5\AccountBundle\Entity\User $user
     * @return Movie
     */
    public function setUser(\g5\AccountBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \g5\AccountBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set backdrop_path
     *
     * @param string $backdropPath
     * @return Movie
     */
    public function setBackdropPath($backdropPath)
    {
        $this->backdrop_path = $backdropPath;

        return $this;
    }

    /**
     * Get backdrop_path
     *
     * @return string
     */
    public function getBackdropPath()
    {
        return $this->backdrop_path;
    }

    /**
     * Set poster_path
     *
     * @param string $posterPath
     * @return Movie
     */
    public function setPosterPath($posterPath)
    {
        $this->poster_path = $posterPath;

        return $this;
    }

    /**
     * Get poster_path
     *
     * @return string
     */
    public function getPosterPath()
    {
        return $this->poster_path;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Movie
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
