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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use g5\MovieBundle\Entity\Label;
use g5\MovieBundle\Entity\MovieLabel;
use g5\MovieBundle\Form\Model\Link;

class ApiController extends Controller
{
    /**
     * Request Label form
     *
     * @return Response
     */
    public function labelNewAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Wrong Request Type.');
        }

        $form = $this->get('g5_movie.link.form');

        return $this->render('g5MovieBundle:Label:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Label lookup function for typeahead
     *
     * @return JsonResponse [description]
     */
    public function labelFindAction()
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

    /**
     * Adds a new Label or Binds an existing label to a movie
     *
     * @return JsonResponse
     */
    public function labelAddAction()
    {
        $formHandler = $this->get('g5_movie.link.form_handler');
        $serializer = $this->get('jms_serializer');

        $label = $formHandler->process(new Link(), $this->getUser());
        if (false !== $label) {
            $jsonData = array(
                'status' => 'OK',
                'message' => 'Label added.',
                'label' => $label,
            );
        } else {
            $jsonData = array(
                'status' => 'ERROR',
                'message' => 'Label could not be added.',
            );
        }

        $data = $serializer->serialize($jsonData, 'json');

        $response = new JsonResponse();
        $response->setContent($data);

        return $response;
    }

    /**
     * Delets a label permanently
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function labelDeleteAction(Request $request)
    {
        $user = $this->getUser();
        $lm = $this->get('g5_movie.label_manager');
        $labelId = $request->query->get('labelId');

        $label = $lm->findLabelBy(array('id' => $labelId, 'user' => $user));

        if (null !== $label) {
            $lm->removeLabel($label);
            $jsonData = array(
                'status' => 'OK',
                'message' => 'Label deleted.',
            );
        } else {
            $jsonData = array(
                'status' => 'ERROR',
                'message' => 'Label could not be deleted.',
            );
        }

        return new JsonResponse($jsonData);
    }

    /**
     * Unlinks a Label from a Movie
     *
     * @return JsonResponse
     */
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

    public function movieUpdateFavoriteAction(Request $request)
    {
        $movieId = $request->query->get('movieId');
        $user = $this->getUser();
        $mm = $this->get('g5_movie.movie_manager');

        $movie = $mm->loadMovieById($movieId, $user);

        if (!$movie) {
            $jsonData = array(
                'status' => 'ERROR',
                'message' => 'Movie does not exist.',
            );
        } else {
            $favorite = ($movie->isFavorite() ? false : true);
            $movie->setFavorite($favorite);
            $mm->updateMovie($movie);

            $jsonData = array(
                'status' => 'OK',
                'message' => 'Movie favorite status changed.',
                'data' => $movie->isFavorite(),
            );
        }

        return new JsonResponse($jsonData);
    }
}
