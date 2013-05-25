<?php

namespace g5\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 */
class Movie
{
    /**
     * @var integer
     */
    private $tmdb_id;

    /**
     * @var integer
     */
    private $user_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $overview;

    /**
     * @var string
     */
    private $cover_url;

    /**
     * @var \DateTime
     */
    private $release_date;

    /**
     * @var \g5\AccountBundle\Entity\User
     */
    private $user;


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
     * Set user_id
     *
     * @param integer $userId
     * @return Movie
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Set cover_url
     *
     * @param string $coverUrl
     * @return Movie
     */
    public function setCoverUrl($coverUrl)
    {
        $this->cover_url = $coverUrl;

        return $this;
    }

    /**
     * Get cover_url
     *
     * @return string 
     */
    public function getCoverUrl()
    {
        return $this->cover_url;
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
     * Set user
     *
     * @param \g5\AccountBundle\Entity\User $user
     * @return Movie
     */
    public function setUser(\g5\AccountBundle\Entity\User $user = null)
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
     * @var integer
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
