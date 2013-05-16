<?php

namespace g5\AccountBundle\Document;

use FOS\UserBundle\Document\User as BaseUser;


/**
 * g5\AccountBundle\Document\User
 */
class User extends BaseUser
{
    /**
     * @var $id
     */
    protected $id;

    /**
     * @var $termsOfService
     */
    protected $termsOfService;

    /**
     * @var g5\AccountBundle\Document\Group
     */
    protected $groups = array();

    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add groups
     *
     * @param g5\AccountBundle\Document\Group $groups
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups[] = $groups;
    }

    /**
    * Remove groups
    *
    * @param <variableType$groups
    */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return Doctrine\Common\Collections\Collection $groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set termsOfService
     *
     * @param boolean $termsOfService
     */
    public function setTermsOfService($termsOfService)
    {
        $this->termsOfService = $termsOfService;
    }

    /**
     * Get termsOfService
     *
     * @return boolean $termsOfService
     */
    public function getTermsOfService()
    {
        return $this->termsOfService;
    }
}
