<?php

namespace g5\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * g5\MovieBundle\Entity\Movie
 */
class Movie
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $tmdb_id
     */
    private $tmdb_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var \DateTime $release
     */
    private $release;

    /**
     * @var string $backdrop_path
     */
    private $backdrop_path;

    /**
     * @var string $overview
     */
    private $overview;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $label;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->label = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Movie
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set release
     *
     * @param \DateTime $release
     * @return Movie
     */
    public function setRelease($release)
    {
        $this->release = $release;
    
        return $this;
    }

    /**
     * Get release
     *
     * @return \DateTime 
     */
    public function getRelease()
    {
        return $this->release;
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
     * Add label
     *
     * @param g5\MovieBundle\Entity\Label $label
     * @return Movie
     */
    public function addLabel(\g5\MovieBundle\Entity\Label $label)
    {
        $this->label[] = $label;
    
        return $this;
    }

    /**
     * Remove label
     *
     * @param g5\MovieBundle\Entity\Label $label
     */
    public function removeLabel(\g5\MovieBundle\Entity\Label $label)
    {
        $this->label->removeElement($label);
    }

    /**
     * Get label
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * Generates a Movie entity by a Tmdb search result
     * 
     * @param \StdClass $movie  The Tmdb Movie result as \StdClass object
     * @return Movie
     */
    public static function generateByTmdbMovie($movie)
    {
        $t_movie = new self();
        $t_movie->setTmdbId($movie->id);
        $t_movie->setName($movie->original_title);
        $t_movie->setRelease(new \DateTime($movie->release_date));
        $t_movie->setOverview($movie->overview);
        $t_movie->setBackdropPath($movie->poster_path);
        
        return $t_movie;
    }
}
