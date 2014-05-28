<?php

namespace g5\MovieBundle\Document;



/**
 * g5\MovieBundle\Document\Label
 */
class Label
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
