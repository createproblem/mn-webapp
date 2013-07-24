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
    public function indexAction($name)
    {
        $lm = $this->get('g5_movie.label_manager');
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $label = $lm->findLabelBy(array('name_norm' => $name));
        $movies = $label->getMovies();

        return $this->render('g5MovieBundle:Label:index.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
        ));
    }
}
