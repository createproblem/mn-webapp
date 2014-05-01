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
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;

class LabelApiController extends Controller
{
    /**
     *
     * @QueryParam(
     *     name="q",
     *     description="Page of the overview.",
     *     requirements="^[a-zA-Z0-9.' ']+$"
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
}
