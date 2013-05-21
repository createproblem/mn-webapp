<?php
// /src/g5/MovieBundle/Controller/MovieController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use g5\MovieBundle\Form\Type\MovieSearchType;
use g5\MovieBundle\Document\Movie;

class MovieController extends Controller
{
    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->createForm(new MovieSearchType());

        if ($request->isMethod('POST')) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                // search
            }
        }

        return $this->render('g5MovieBundle:Movie:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function searchAction()
    {
        $request = $this->getRequest();
        $form = $this->createForm(new MovieSearchType());

        // if GET return search template
        if (!$request->isXmlHttpRequest()) {
            return $this->render('g5MovieBundle:Movie:search.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        $form->bindRequest($request);
        if ($form->isValid()) {
            $tmdb = $this->get('g5.movie.tmdb_api');
            $formData = $form->getData();
            $res = $tmdb->searchMovie($formData['search']);
            return new JsonResponse($res);
        } else {
            return $this->createNotFoundException();
        }
    }
}
