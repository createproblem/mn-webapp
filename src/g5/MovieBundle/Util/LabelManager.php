<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Util;

use g5\MovieBundle\Entity\Label;

class LabelManager
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    public $repository;

    /**
     * Constructor.
     *
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Doctrine\ORM\EntityManager    $em
     */
    public function __construct($repository, $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @return Label
     */
    public function createLabel()
    {
        return new Label();
    }

    /**
     * @param  Label  $label
     */
    public function updateLabel(Label $label)
    {
        $this->em->persist($label);
        $this->em->flush();
    }

    /**
     * @param  Label $label
     */
    public function removeLabel(Label $label)
    {
        $this->em->remove($label);
        $this->em->flush();
    }
}
