<?php
// /src/g5/AccountBundle/Form/Type/RegistrationFormHandler.php

namespace g5\AccountBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseRegistrationFormHandler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface;

use FOS\UserBundle\Model\GroupManagerInterface;

class RegistrationFormHandler extends BaseRegistrationFormHandler
{
    protected $groupManager;

    public function __construct(Form $form, Request $request, UserManagerInterface $userManager,
        MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, GroupManagerInterface $groupManager)
    {
        parent::__construct($form, $request, $userManager, $mailer, $tokenGenerator);
        $this->groupManager = $groupManager;
    }

    protected function onSuccess(UserInterface $user, $confirmation)
    {
        $memberGroup = $this->groupManager->findGroupByName('member');

        if ($memberGroup) {
            $user->addGroup($memberGroup);
        }

        parent::onSuccess($user, $confirmation);
    }
}
