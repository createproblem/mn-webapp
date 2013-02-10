<?php

namespace g5\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        return $this->render('g5AccountBundle:Registration:index.html.twig');
    }
}
