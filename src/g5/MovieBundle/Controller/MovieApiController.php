<?php

 /**
  * This file is part of the mn-webapp package.
  *
  * (c) createproblem <https://github.com/createproblem/>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  */

namespace g5\MovieBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

class MovieApiController extends FOSRestController
{
    /**
     * @RestAnnotation\QueryParam(
     *     name="page",
     *     strict=false,
     *     requirements="^[0-9]+$"
     * )
     *
     * @RestAnnotation\QueryParam(
     *     name="max",
     *     requirements="^[0-9]+$"
     * )
     *
     * @ApiDoc(
     *     description="Returns all movies.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     */
    public function getMoviesAction(ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        $page = $paramFetcher->get('page') === null ? 1 : (int)$paramFetcher->get('page');
        $max = $paramFetcher->get('max') === null ? 100 : (int)$paramFetcher->get('max');
        $skip = $page - 1;

        $mm = $this->get('g5_movie.movie_manager');
        $moviesCursor = $mm->repository->findPaginated($user, $max, $max * $skip);

        $data = array(
            'total_results' => $moviesCursor->count(),
            'page' => $page,
            'max_pages' => ceil($moviesCursor->count() / $max),
            'max_items' => $max,
            'movies' => $moviesCursor->toArray(false),
        );

        $view = View::create();

        if (empty($data['movies'])) {
            $status = \FOS\RestBundle\Util\Codes::HTTP_NOT_FOUND;
        } else {
            $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
            $view->setData($data);
        }

        $view->setStatusCode($status);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @RestAnnotation\RequestParam(
     *     name="tmdbId",
     *     strict=true,
     *     nullable=false,
     *     requirements="^[0-9]+$"
     * )
     *
     * @ApiDoc(
     *     description="Returns all movies.",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when movie cannot be saved"
     *     }
     * )
     */
    public function postMovieAction(ParamFetcher $paramFetcher)
    {
        $api = $this->get('g5_tmdb.api.default');
        $movieManager = $this->get('g5_movie.movie_manager');
        $validator = $this->get('validator');
        $params = $paramFetcher->all();

        $result = $api->getMovie(array('id' => $params['tmdbId']));

        $movie = $movieManager->createMovieFromTmdb($result);
        $movie->setUser($this->getUser());

        $errors = $validator->validate($movie);
        if (count($errors) === 0) {
            $movieManager->updateMovie($movie);
            $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
            $data = $movie;
        } else {
            $status = \FOS\RestBundle\Util\Codes::HTTP_BAD_REQUEST;
            $data = $errors;
        }

        $view = View::create()
            ->setStatusCode($status)
            ->setData($data)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
