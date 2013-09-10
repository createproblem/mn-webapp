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
        $lm = $this->get('g5_movie.label_manager');
        $user = $this->getUser();
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $text = "Hello World"; //10
        $truncated = (strlen($text) > 5) ? substr($text, 0, 20) . '...' : $text;

        $labels = $lm->findLabelsBy(array('user' => $user), array('movie_count' => 'DESC'));
        $latestMovies = $mm->loadLatestMovies($user);
        $randomMovies = $mm->loadRandomMovies($user, 25);
        shuffle($randomMovies);

        return $this->render('g5HomeBundle:Default:test.html.twig', array(
            'latestMovies' => $latestMovies,
            'labels' => $labels,
            'randomMovies' => $randomMovies,
            'imgUrl_w1280' => $tmdbApi->getImageUrl('w1280'),
            'imgUrl_w92' => $tmdbApi->getImageUrl('w92'),
        ));
    }
}
