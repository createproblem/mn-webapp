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
     * @var int $tmdbid
     */
    protected $tmdbid;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $overview
     */
    protected $overview;

    /**
     * @var hash $meta
     */
    protected $meta;


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
     * Set tmdbid
     *
     * @param int $tmdbid
     * @return self
     */
    public function setTmdbid($tmdbid)
    {
        $this->tmdbid = $tmdbid;
        return $this;
    }

    /**
     * Get tmdbid
     *
     * @return int $tmdbid
     */
    public function getTmdbid()
    {
        return $this->tmdbid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set overview
     *
     * @param string $overview
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
     * @return string $overview
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set meta
     *
     * @param hash $meta
     * @return self
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get meta
     *
     * @return hash $meta
     */
    public function getMeta()
    {
        return $this->meta;
    }
    /**
     * @var string $title
     */
    protected $title;


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
     * @var string $cover
     */
    protected $cover;


    /**
     * Set cover
     *
     * @param string $cover
     * @return self
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * Get cover
     *
     * @return string $cover
     */
    public function getCover()
    {
        return $this->cover;
    }
    /**
     * @var string $cover_url
     */
    protected $cover_url;

    /**
     * @var date $release_date
     */
    protected $release_date;


    /**
     * Set cover_url
     *
     * @param string $coverUrl
     * @return self
     */
    public function setCoverUrl($coverUrl)
    {
        $this->cover_url = $coverUrl;
        return $this;
    }

    /**
     * Get cover_url
     *
     * @return string $coverUrl
     */
    public function getCoverUrl()
    {
        return $this->cover_url;
    }

    /**
     * Set release_date
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
     * Get release_date
     *
     * @return date $releaseDate
     */
    public function getReleaseDate()
    {
        return $this->release_date;
    }
}
