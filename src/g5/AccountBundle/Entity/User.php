<?php

namespace g5\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


use FOS\UserBundle\Entity\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $movies;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $labels;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
        parent::__construct();
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
     * Add movies
     *
     * @param \g5\MovieBundle\Entity\Movie $movies
     * @return User
     */
    public function addMovie(\g5\MovieBundle\Entity\Movie $movies)
    {
        $this->movies[] = $movies;

        return $this;
    }

    /**
     * Remove movies
     *
     * @param \g5\MovieBundle\Entity\Movie $movies
     */
    public function removeMovie(\g5\MovieBundle\Entity\Movie $movies)
    {
        $this->movies->removeElement($movies);
    }

    /**
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * Add labels
     *
     * @param \g5\MovieBundle\Entity\Label $labels
     * @return User
     */
    public function addLabel(\g5\MovieBundle\Entity\Label $labels)
    {
        $this->labels[] = $labels;

        return $this;
    }

    /**
     * Remove labels
     *
     * @param \g5\MovieBundle\Entity\Label $labels
     */
    public function removeLabel(\g5\MovieBundle\Entity\Label $labels)
    {
        $this->labels->removeElement($labels);
    }

    /**
     * Get labels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLabels()
    {
        return $this->labels;
    }
}
