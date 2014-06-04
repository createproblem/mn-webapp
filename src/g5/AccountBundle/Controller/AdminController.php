<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('g5AccountBundle:Admin:index.html.twig', array(
            'users' => $users
        ));
    }

    public function generateTokenAction(Request $request)
    {
    	$tm = $this->get('fos_oauth_server.access_token_manager');
    	$cm = $this->get('fos_oauth_server.client_manager');
    	$um = $this->get('fos_user.user_manager');
    	$oauth = $this->get('fos_oauth_server.server');
    	$serializer = $this->get('jms_serializer');

    	$client = $cm->findClientBy(array('name' => 'TestClient'));
    	$user = $um->findUserBy(array('id' => $request->query->get('user_id')));

    	if (null === $user) {
    		throw $this->createNotFoundException('User not found');
    	}

    	$access_token = $oauth->createAccessToken($client, $user);

    	$response = new JsonResponse();
    	$response->setData($access_token);

    	return $response;
    }
}
