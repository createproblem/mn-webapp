<?php

namespace g5\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Label
 */
class Label
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $movieLabels;

    /**
     * @var \g5\AccountBundle\Entity\User
     */
    private $user;

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
     * Set name
     *
     * @param string $name
     * @return Label
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
     * Add movieLabels
     *
     * @param \g5\MovieBundle\Entity\MovieLabel $movieLabels
     * @return Label
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
     * @return Label
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
}
