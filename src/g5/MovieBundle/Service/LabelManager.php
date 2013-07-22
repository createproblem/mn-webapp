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
    private $repository;

    public function __construct($doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->repository = $this->em->getRepository('g5MovieBundle:Label');
    }

    /**
     * @return Label
     */
    public function createLabel()
    {
        return new Label();
    }

    /**
     * {@inheritDoc}
     */
    public function findLabelsByNameWithLike($name, \g5\AccountBundle\Entity\User $user)
    {
        return $this->repository->findByNameWithLike($name, $user);
    }

    /**
     * @param  int                           $id
     * @param  \g5\AccountBundle\Entity\User $user [description]
     *
     * @return \g5\MovieBundle\Entity\Label
     */
    public function loadLabelById($id, \g5\AccountBundle\Entity\User $user)
    {
        return $this->repository->findOneBy(array('id' => $id, 'user' => $user));
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
