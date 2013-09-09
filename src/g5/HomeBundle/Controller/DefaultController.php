<?php

namespace g5\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $tmdbApi = $this->get('g5_tools.tmdb.api');
        $result = $tmdbApi->getMovie(277);
        // var_dump($tmdbApi->dumpUrl());
        // var_dump($tmdbApi->getImageUrl('w300').$result['backdrop_path']);
        // var_dump($result);

        return $this->render('g5HomeBundle:Default:test.html.twig');
    }
}
