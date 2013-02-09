<?php

namespace g5\MovieBundle\Document;



/**
 * g5\MovieBundle\Document\Labelm
 */
class Labelm
{
    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;
    
    /**
     * @var string $name
     */
    protected $color;

    /**
     * @var g5\MovieBundle\Document\Moviem
     */
    protected $movies = array();

    public function __construct()
    {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Labelm
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
     * Add movies
     *
     * @param g5\MovieBundle\Document\Moviem $movies
     */
    public function addMovies(\g5\MovieBundle\Document\Moviem $movies)
    {
        $this->movies[] = $movies;
    }

    /**
     * Get movies
     *
     * @return Doctrine\Common\Collections\Collection $movies
     */
    public function getMovies()
    {
        return $this->movies;
    }
  
    /**
     * Set color
     *
     * @param string $color
     * @return Labelm
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Get color
     *
     * @return string $color
     */
    public function getColor()
    {
        return $this->color;
    }
}
