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
    private $id;

    /**
     * @var integer
     */
    private $tmdbid;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tmdbid
     *
     * @param integer $tmdbid
     * @return Movie
     */
    public function setTmdbid($tmdbid)
    {
        $this->tmdbid = $tmdbid;

        return $this;
    }

    /**
     * Get tmdbid
     *
     * @return integer 
     */
    public function getTmdbid()
    {
        return $this->tmdbid;
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
}
