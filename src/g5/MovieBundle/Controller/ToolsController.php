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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ToolsController extends Controller
{
    public function indexAction()
    {
        return $this->render('g5MovieBundle:Tools:index.html.twig');
    }
}
