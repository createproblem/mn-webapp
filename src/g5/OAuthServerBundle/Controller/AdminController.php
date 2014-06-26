<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\OAuthServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $repository = $this->get('doctrine_mongodb')->getRepository('g5OAuthServerBundle:Client');

        $clients = $repository->findAll();

        return $this->render('g5OAuthServerBundle:Admin:index.html.twig', array(
            'clients' => $clients
        ));
    }
}
