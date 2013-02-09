<?php

namespace g5\MovieBundle\Document;



/**
 * g5\MovieBundle\Document\Moviem
 */
class Moviem
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
     * @var string $name
     */
    protected $name;

    /**
     * @var date $release
     */
    protected $release;

    /**
     * @var string $backdrop_path
     */
    protected $backdrop_path;

    /**
     * @var string $overview
     */
    protected $overview;

    /**
     * @var g5\MovieBundle\Document\Labelm
     */
    protected $labels = array();

    public function __construct()
    {
        $this->labels = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set tmdb_id
     *
     * @param int $tmdbId
     * @return Moviem
     */
    public function setTmdbId($tmdbId)
    {
        $this->tmdb_id = $tmdbId;
        return $this;
    }

    /**
     * Get tmdb_id
     *
     * @return int $tmdbId
     */
    public function getTmdbId()
    {
        return $this->tmdb_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Moviem
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
     * Set release
     *
     * @param date $release
     * @return Moviem
     */
    public function setRelease($release)
    {
        $this->release = $release;
        return $this;
    }

    /**
     * Get release
     *
     * @return date $release
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * Set backdrop_path
     *
     * @param string $backdropPath
     * @return Moviem
     */
    public function setBackdropPath($backdropPath)
    {
        $this->backdrop_path = $backdropPath;
        return $this;
    }

    /**
     * Get backdrop_path
     *
     * @return string $backdropPath
     */
    public function getBackdropPath()
    {
        return $this->backdrop_path;
    }

    /**
     * Set overview
     *
     * @param string $overview
     * @return Moviem
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
     * Add labels
     *
     * @param g5\MovieBundle\Document\Labelm $labels
     */
    public function addLabels(\g5\MovieBundle\Document\Labelm $labels)
    {
        $this->labels[] = $labels;
    }

    /**
     * Get labels
     *
     * @return Doctrine\Common\Collections\Collection $labels
     */
    public function getLabels()
    {
        return $this->labels;
    }
}
