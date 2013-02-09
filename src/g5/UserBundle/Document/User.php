<?php

namespace g5\UserBundle\Document;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * g5\UserBundle\Document\User
 */
class User extends BaseUser
{
    /**
     * @var MongoId $id
     */
    protected $id;

    public function __construct()
    {
        // Execute FOSUserBundle logic
        parent::__construct();
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
}
