<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Entity;

use g5\MovieBundle\Entity\Model\LabelAbstract as LabelBase;

/**
 * Label
 */
class Label extends LabelBase
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
     * @var string
     */
    private $name_norm;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $movieLabels;

    /**
     * @var \g5\AccountBundle\Entity\User
     */
    private $user;

    /**
     * @var integer
     */
    private $movie_count = 0;

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

    /**
     * Set name_norm
     *
     * @param string $nameNorm
     * @return Label
     */
    public function setNameNorm($nameNorm)
    {
        $this->name_norm = $nameNorm;

        return $this;
    }

    /**
     * Get name_norm
     *
     * @return string
     */
    public function getNameNorm()
    {
        return $this->name_norm;
    }

    /**
     * Set movie_count
     *
     * @param integer $movieCount
     * @return Label
     */
    public function setMovieCount($movieCount)
    {
        $this->movie_count = $movieCount;

        return $this;
    }

    /**
     * Get movie_count
     *
     * @return integer
     */
    public function getMovieCount()
    {
        return $this->movie_count;
    }
}