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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Entity\Movie;

class MovieController extends Controller
{
    /**
     * Search for new Movie
     *
     * @param  Request $request [description]
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(new SearchType());
        $result = array();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $query = $form->get('search')->getData();
                $api = $this->get('g5_tmdb.api.default');
                $result = $api->getSearchMovie(array('query' => $query));
            }
        }

        return $this->render('g5MovieBundle:Movie:new.html.twig', array(
            'form' => $form->createView(),
            'searchResult' => $result,
        ));
    }

}
