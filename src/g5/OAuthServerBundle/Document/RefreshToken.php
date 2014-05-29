<?php

namespace g5\OAuthServerBundle\Document;

use FOS\OAuthServerBundle\Document\RefreshToken as BaseRefreshToken;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * g5\OAuthServerBundle\Document\RefreshToken
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var UserInterface
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
     * Set client
     *
     * @param ClientInterface $client
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return ClientInterface $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param UserInterface $user
     * @return self
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return UserInterface $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
