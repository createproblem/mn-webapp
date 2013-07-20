<?php

namespace g5\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieLabel
 */
class MovieLabel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \g5\MovieBundle\Entity\Movie
     */
    private $movie;

    /**
     * @var \g5\MovieBundle\Entity\Label
     */
    private $label;


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
     * Set movie
     *
     * @param \g5\MovieBundle\Entity\Movie $movie
     * @return MovieLabel
     */
    public function setMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \g5\MovieBundle\Entity\Movie 
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set label
     *
     * @param \g5\MovieBundle\Entity\Label $label
     * @return MovieLabel
     */
    public function setLabel(\g5\MovieBundle\Entity\Label $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return \g5\MovieBundle\Entity\Label 
     */
    public function getLabel()
    {
        return $this->label;
    }
}
