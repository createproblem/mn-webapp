<?php

namespace g5\OAuthServerBundle\Document;

use FOS\OAuthServerBundle\Document\Client as BaseClient;

/**
 * g5\OAuthServerBundle\Document\Client
 */
class Client extends BaseClient
{
    /**
     * @var MongoId $id
     */
    protected $id;

    public function __construct()
    {
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
    /**
     * @var string $name
     */
    protected $name;


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
}
