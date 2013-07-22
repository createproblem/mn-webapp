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
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Wrong Request Type.');
        }

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
        $labels = $labelManager->findLabelsByNameWithLike($name, $user);

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
            $label->setUser($user);
            $movieId = $form->get('movie_id')->getData();

            if ($movieId) {
                $movieManager = $this->get('g5_movie.movie_manager');
                $movie = $movieManager->loadMovieById($movieId, $user);
                $label->addMovie($movie);
            }
            $lm->updateLabel($label);

            $serializer = $this->get('jms_serializer');

            $jsonData = array(
                'status' => 'OK',
                'message' => 'New label added.',
                'label' => $label,
                'movieId' => $movie->getId()
            );

            $data = $serializer->serialize($jsonData, 'json');

            $response = new JsonResponse();
            $response->setContent($data);

            return $response;
        }

        return new JsonResponse($form->getErrorsAsString());
    }

    public function unlinkAction()
    {
        $request = $this->getRequest();
        $labelManager = $this->get('g5_movie.label_manager');
        $mm = $this->get('g5_movie.movie_manager');
        $em = $this->getDoctrine()->getManager();


        $user = $this->getUser();

        $labelId = $request->query->get('labelId');
        $movieId = $request->query->get('movieId');

        $label = $labelManager->loadLabelById($labelId, $user);
        $movie = $mm->loadMovieById($movieId, $user);
        if ($label && $movie) {
            $movie->removeLabel($label);
            $mm->updateMovie($movie);

            $data['status'] = 'OK';

            return new JsonResponse($data);
        }
        $data['status'] = 'ERROR';

        return new JsonResponse($data);
    }
}
