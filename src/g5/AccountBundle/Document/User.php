<?php

namespace g5\AccountBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * g5\AccountBundle\Document\User
 */
class User extends BaseUser
{
    /**
     * @var MongoId $id
     */
    protected $id;


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
