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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as RestAnnotation;
use FOS\RestBundle\View\View;

use g5\MovieBundle\Form\Model\Link;

class ApiController extends Controller
{

    /**
     * Get movie data from Tmdb.
     *
     * @ApiDoc(
     *     description="Get movie data from Tmdb.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @param  Request  $request
     * @param  integer  $tmdbId  The movie tmdbId.
     */
    public function getMovieTmdbAction(Request $request, $tmdbId)
    {
        // if (!$request->isXmlHttpRequest()) {
        //     throw $this->createNotFoundException('Wrong Request Type.');
        // }

        $tmdbApi = $this->get('g5_tmdb.api.default');
        $movieResult = $tmdbApi->getMovie(array('id' => (int)$tmdbId));

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
        $data = $movieResult->toArray();

        $view = View::create()
            ->setStatusCode($status)
            ->setData($data)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Adds a new movie from tmdb.
     *
     * @param string $tmdbId integer with the page number (requires param_fetcher_listener: force)
     *
     * @ApiDoc(
     *     description="Adds a new movie from tmdb.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @RestAnnotation\RequestParam(
     *     name="tmdbId",
     *     description="TmdbId of the movie.",
     *     strict=true,
     *     nullable=false,
     *     requirements="^\d+$"
     * )
     *
     */
    public function postMovieAction(ParamFetcher $paramFetcher)
    {
        $tmdbApi = $this->get('g5_tmdb.api.default');
        $movieManager = $this->get('g5_movie.movie_manager');
        $validator = $this->get('validator');

        $params = $paramFetcher->all();

        $result = $tmdbApi->getMovie(array('id' => (int)$params['tmdbId']));
        $movie = $movieManager->createMovieFromTmdb($result);
        $movie->setUser($this->getUser());

        $errors = $validator->validate($movie);

        if (count($errors) === 0) {
            $movieManager->updateMovie($movie);
            $data = $movie;
        } else {
            $data = $errors;
        }

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
        $view = View::create()
            ->setStatusCode($status)
            ->setData($data)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    public function getMovieLabelFormAction($id)
    {
        $movieManager = $this->get('g5_movie.movie_manager');
        $movie = $movieManager->find($id);

        $link = new Link();
        $link->setMovieId($movie->getId());

        $form = $this->createForm('link', $link);
        $data = array('form' => $form->createView());

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
        $view = View::create($data)
            ->setStatusCode($status)
            ->setTemplate('g5MovieBundle:Label:new.html.twig')
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @RestAnnotation\RequestParam(
     *     name="labelId",
     *     strict=false,
     *     requirements="^\d+$"
     * )
     */
    public function postMovieLabelAction($id, ParamFetcher $paramFetcher)
    {
        $labelManager = $this->get('g5_movie.label_manager');
        $movieManager = $this->get('g5_movie.movie_manager');
        $validator = $this->get('validator');

        $params = $paramFetcher->all();
        $labelId = $params['labelId'];

        $label = $labelManager->findByUser($labelId, $this->getUser());
        $movie = $movieManager->find($id);

        $movieLabel = $movie->addLabel($label);

        $errors = $validator->validate($movieLabel);
        if (count($errors) === 0) {
            $movieManager->updateMovie($movie);
            $data = array('label' => $label);
        } else {
            $data = array('errors' => $errors);
        }

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;

        $view = View::create($data)
            ->setStatusCode($status)
            ->setTemplate('g5MovieBundle:Label:label.html.twig')
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
