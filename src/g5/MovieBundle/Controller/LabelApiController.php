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

class LabelApiController extends Controller
{
    /**
     *
     * @RestAnnotation\QueryParam(
     *     name="q",
     *     description="Query for the label name."
     * )
     *
     * @ApiDoc(
     *     description="Get labels.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @param ParamFetcher $paramFetcher
     */
    public function getLabelsAction($q)
    {
        $labelManager = $this->get('g5_movie.label_manager');
        $labels = $labelManager->findLabelsByNameWithLike($q, $this->getUser());

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;

        $data = $q;
        $view = View::create()
            ->setStatusCode($status)
            ->setData($labels)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @RestAnnotation\QueryParam(
     *     name="unused",
     *     description="Filter labels by movie_count equals 0.",
     *     requirements="^(true|false)$",
     *     strict=true
     * )
     *
     * @ApiDoc(
     *     description="Delets labels.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @param ParamFetcher $paramFetcher
     */
    public function deleteLabelsAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $lm = $this->get('g5_movie.label_manager');

        $criteria = array('user' => $this->getUser()->getId());

        if ('true' === $params['unused']) {
            $criteria['movie_count'] = 0;
        }

        $labels = $lm->repository->findBy($criteria);
        $deleted = count($labels);

        foreach ($labels as $label) {
            $lm->removeLabel($label);
        }

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;

        $view = View::create(array('labels_deleted' => $deleted))
            ->setStatusCode($status)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
