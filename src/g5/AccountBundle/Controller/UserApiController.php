<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\AccountBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;

class UserApiController extends FOSRestController
{
    public function meAction()
    {
        $user = $this->getUser();

        $status = \FOS\RestBundle\Util\Codes::HTTP_OK;
        $view = View::create()
            ->setStatusCode($status)
            ->setData($user)
        ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
