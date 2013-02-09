<?php

namespace g5\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('g5UserBundle:Default:index.html.twig');
    }
}
