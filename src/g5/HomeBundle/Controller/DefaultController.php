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

        $mm = $this->get('g5_movie.movie_manager');
        $user = $this->getUser();
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        // $text = "Hello World"; //10
        // $truncated = (strlen($text) > 5) ? substr($text, 0, 20) . '...' : $text;
        $latestMovies = $mm->loadLatestMovies($user);

        return $this->render('g5HomeBundle:Default:test.html.twig', array(
            'latestMovies' => $latestMovies,
            'imgUrl_w1280' => $tmdbApi->getImageUrl('w780'),
        ));
    }
}
