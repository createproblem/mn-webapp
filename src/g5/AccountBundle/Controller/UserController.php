<?php

namespace g5\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function meAction() {
        $user = $this->getUser();

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($user, 'json');

        $response = new JsonResponse();
        $response->setContent($data);

        return $response;
    }
}
