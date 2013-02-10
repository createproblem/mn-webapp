<?php
// src/g5/AccountBundle/Form/Model

namespace g5\AccountBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use g5\AccountBundle\Document\User;

class Registration
{
    /**
     * @Assert\Type(type="g5\AccountBundle\Document\User")
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean)$termsAccepted;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }
}
