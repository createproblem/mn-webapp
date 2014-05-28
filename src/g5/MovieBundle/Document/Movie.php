<?php

namespace g5\MovieBundle\Document;



/**
 * g5\MovieBundle\Document\Movie
 */
class Movie
{
    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var int $tmdb_id
     */
    protected $tmdb_id;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $overview
     */
    protected $overview;

    /**
     * @var string $poster_path
     */
    protected $poster_path;

    /**
     * @var string $backdrop_path
     */
    protected $backdrop_path;

    /**
     * @var date $release_date
     */
    protected $release_date;

    /**
     * @var date $created_at
     */
    protected $created_at;

    /**
     * @var int $label_count
     */
    protected $label_count;

    /**
     * @var boolean $favorite
     */
    protected $favorite;

    /**
     * @var g5\AccountBundle\Document\User
     */
    protected $user;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tmdbId
     *
     * @param integer $tmdbId
     * @return self
     */
    public function setTmdbId($tmdbId)
    {
        $this->tmdb_id = $tmdbId;
        return $this;
    }

    /**
     * Get tmdbId
     *
     * @return integer $tmdbId
     */
    public function getTmdbId()
    {
        return $this->tmdb_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set overview
     *
     * @param text $overview
     * @return self
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;
        return $this;
    }

    /**
     * Get overview
     *
     * @return text $overview
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set posterPath
     *
     * @param string $posterPath
     * @return self
     */
    public function setPosterPath($posterPath)
    {
        $this->poster_path = $posterPath;
        return $this;
    }

    /**
     * Get posterPath
     *
     * @return string $posterPath
     */
    public function getPosterPath()
    {
        return $this->poster_path;
    }

    /**
     * Set backdropPath
     *
     * @param string $backdropPath
     * @return self
     */
    public function setBackdropPath($backdropPath)
    {
        $this->backdrop_path = $backdropPath;
        return $this;
    }

    /**
     * Get backdropPath
     *
     * @return string $backdropPath
     */
    public function getBackdropPath()
    {
        return $this->backdrop_path;
    }

    /**
     * Set releaseDate
     *
     * @param date $releaseDate
     * @return self
     */
    public function setReleaseDate($releaseDate)
    {
        $this->release_date = $releaseDate;
        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return date $releaseDate
     */
    public function getReleaseDate()
    {
        return $this->release_date;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set labelCount
     *
     * @param integer $labelCount
     * @return self
     */
    public function setLabelCount($labelCount)
    {
        $this->label_count = $labelCount;
        return $this;
    }

    /**
     * Get labelCount
     *
     * @return integer $labelCount
     */
    public function getLabelCount()
    {
        return $this->label_count;
    }

    /**
     * Set favorite
     *
     * @param boolean $favorite
     * @return self
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean $favorite
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set user
     *
     * @param g5\AccountBundle\Document\User $user
     * @return self
     */
    public function setUser(\g5\AccountBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return g5\AccountBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
