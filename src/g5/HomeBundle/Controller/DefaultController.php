<?php

namespace g5\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if (null === $this->getUser()) {
            return $this->render('g5HomeBundle:Default:index.html.twig');
        }

        $serializer = $this->get('jms_serializer');
        $movieManager = $this->get('g5_movie.movie_manager');
        $movies = $movieManager->findMoviesByUser($this->getUser());

        foreach ($movies as $movie) {
            $labels[$movie->getId()] = $movie->getLabels();
        }

        $labels = $serializer->serialize($labels, 'json');

        return $this->render('g5HomeBundle:Default:test.html.twig', array(
            'movies' => $movies,
            'labels' => $labels
        ));
    }
}
