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

    public function findAction()
    {
        $request = $this->getRequest();
        $user = $this->getUser();
        $name = $request->query->get('query');

        $labelManager = $this->get('g5_movie.label_manager');
        $labels = $labelManager->findLabelNamesTypeahead($name, $this->getUser());

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize(array('labels' => $labels), 'json');

        $response = new JsonResponse();
        $response->setContent($data);

        return $response;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $user = $this->getUser();

        $lm = $this->get('g5_movie.label_manager');
        $label = $lm->createLabel();

        $form = $this->createForm('label', $label);
        $form->bind($request);

        if ($form->isValid()) {
            $label = $form->getData();
            $movieId = $form->get('movie_id')->getData();
            if ($movieId) {
                $movieManager = $this->get('g5_movie.movie_manager');
                $movie = $movieManager->findById($movieId);
                $label->addMovie($movie);
            }
            $label->setUser($user);
            $label = $lm->update($label);

            $serializer = $this->get('jms_serializer');

            $jsonData = array(
                'status' => 'OK',
                'message' => 'New label added.',
                'label' => $label,
                'movieId' => $movieId,
            );

            $data = $serializer->serialize($jsonData, 'json');

            $response = new JsonResponse();
            $response->setContent($data);

            return $response;
        }

        return new JsonResponse($form->getErrorsAsString());
    }
}
