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

use g5\MovieBundle\Document\Label;

class LabelManager
{
    /**
     * @var Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var Doctrine\ODM\DocumentRepository
     */
    public $repository;

    /**
     * Constructor.
     *
     * @param Doctrine\ODM\DocumentRepository         $repository
     * @param Doctrine\ODM\MongoDB\DocumentManager    $dm
     */
    public function __construct($repository, $dm)
    {
        $this->repository = $repository;
        $this->dm = $dm;
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
        $this->dm->persist($label);
        $this->dm->flush();
    }

    /**
     * @param  Label $label
     */
    public function removeLabel(Label $label)
    {
        $this->dm->remove($label);
        $this->dm->flush();
    }
}
