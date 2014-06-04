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
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes as HttpCodes;

class TmdbApiController extends FOSRestController
{
    /**
     * @RestAnnotation\QueryParam(
     *     name="query",
     *     description="The Query you're looking for",
     *     strict=true,
     *     nullable=true,
     *     default=null,
     *     requirements="[a-zA-Z \s 0-9]+"
     * )
     *
     * @ApiDoc(
     *     description="Search a movie by title at tmdb.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     */
    public function searchAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $query = $params['query'];

        if (null === $query) {
            $status = HttpCodes::HTTP_INTERNAL_SERVER_ERROR;
            $data = array('error' => 'Wrong parameters', 'error_description' => 'Query parameter cannot be null.');
            $view = View::create()
                ->setStatusCode($status)
                ->setData($data)
            ;

            return $this->get('fos_rest.view_handler')->handle($view);
        }

        $api = $this->get('g5_tmdb.api.default');
        $result = $api->getSearchMovie(array('query' => $query));
        $status = HttpCodes::HTTP_OK;

        $view = View::create($result)
            ->setStatusCode($status)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
