<?php
// src/g5/AccountBundle/Controller/RegistrationController.php

namespace g5\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use g5\AccountBundle\Form\Type\RegistrationType;
use g5\AccountBundle\Form\Model\Registration;
use g5\AccountBundle\Document\User;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        $form = $this->createForm(new RegistrationType(), new Registration());

        return $this->render('g5AccountBundle:Registration:index.html.twig', array(
            'form' => $form->createView(),
            'errors' => array()
        ));
    }

    public function createAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $userRepository = $dm->getRepository('g5AccountBundle:User');

        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $registration = $form->getData();

            $validator = $this->get('validator');
            $errors = $validator->validate($registration->getUser());
            if (count($errors) > 0) {
                return $this->render('g5AccountBundle:Registration:index.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $errors
                ));
            }

            $dm->persist($registration->getUser());
            $dm->flush();

            return $this->redirect('/');
        }

        // form isn't valid
        return $this->render('g5AccountBundle:Registration:index.html.twig', array(
            'form' => $form->createView(),
            'errors' => array()
        ));
    }
}
