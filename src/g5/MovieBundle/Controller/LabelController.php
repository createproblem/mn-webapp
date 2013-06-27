<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use g5\MovieBundle\Entity\Label;

class LabelController extends Controller
{
    public function newAction()
    {
        $form = $this->createForm('label', new Label());

        return $this->render('g5MovieBundle:Label:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function getAction()
    {
        $request = $this->getRequest();

        // if (!$request->isXmlHttpRequest()) {
        //     return $this->createNotFoundException('Method not allowed.');
        // }

        $name = $request->query->get('query');

        // $labelManager = $this->get('g5_movie.label_manager');
        // $labels = $labelManager->findLabelTypeahead($name, $this->getUser());

        $labels = $this->getDoctrine()
            ->getRepository('g5MovieBundle:Label')
            ->findAll();

        if (!$labels) {
            throw $this->createNotFoundException('Label not found.');
        }

        return new Response(print_r($labels, true));
    }
}
