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
     * @var \g5\AccountBundle\Entity\User
     */
    private $user;


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
     * Set user
     *
     * @param \g5\AccountBundle\Entity\User $user
     * @return Label
     */
    public function setUser(\g5\AccountBundle\Entity\User $user = null)
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
