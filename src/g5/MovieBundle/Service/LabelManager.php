<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Service;

use g5\AccountBundle\Entity\User;

class LabelManager
{
    private $em;

    private $repository;

    public function __construct($em)
    {
        // $this->em = $em;
        // $this->repository = $em->getRepository('g5MovieBundle:Label');
    }

    public function findLabelTypeahead($name, User $user)
    {
        // $labels = $this->repository->find(7);

        // return $labels;
    }
}