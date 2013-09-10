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
    public function indexAction($name, $page)
    {
        $lm = $this->get('g5_movie.label_manager');
        $mm = $this->get('g5_movie.movie_manager');
        $user = $this->getUser();
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $label = $lm->findLabelBy(array('user' => $user, 'name_norm' => $name));

        if (null === $label) {
            throw $this->createNotFoundException('Label does not exist.');
        }

        $movieCount = count($label->getMovies());
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $lastPage = ceil($movieCount / $limit);


        $movies = $mm->findMoviesByLabel($label, null, $limit, $offset);

        $pagination = array(
            'page' => $page,
            'page_items' => $limit,
            'item_count' => $movieCount,
            'url' => array(
                'route' => 'g5_movie_label_index',
                'params' => array(
                    ':page' => 'page',
                    'name' => $label->getNameNorm(),
                ),
            ),
        );

        return $this->render('g5MovieBundle:Label:index.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
            'pagination' => $pagination,
            'curLabel' => $label,
        ));
    }
}
