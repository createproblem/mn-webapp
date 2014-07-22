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

class LabelApiController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     description="Returns all labels.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     */
    public function getLabelsAction(ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();
        $lm = $this->get('g5_movie.label_manager');
        $labels = $lm->repository->findBy(array('user.id' => $user->getId()));

        $view = View::create();
        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
        $view->setStatusCode($status);
        $view->setData($labels);

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
