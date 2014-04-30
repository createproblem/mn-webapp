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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /**
     * Loads additional movie data.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function loadAdditionalDataAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Wrong Request Type.');
        }

        $tmdbApi = $this->get('g5_tmdb.api.default');
        $tmdbId = (int)$request->query->get('tmdbId');

        $movieResult = $tmdbApi->getMovie(array('id' => $tmdbId));

        $response = new JsonResponse($movieResult->toArray());
        return $response;
    }
}
