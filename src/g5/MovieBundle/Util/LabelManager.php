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
     * @param  array    $criteria
     * @param  array    $orderBy
     * @param  integer  $limit
     * @param  integer  $offset
     *
     * @return array
     */
    public function findLabelsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param  array  $criteria
     *
     * @return Label|null
     */
    public function findLabelBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
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
    public function find($id, \g5\AccountBundle\Entity\User $user = null)
    {
        $criteria['id'] = $id;

        if (null !== $user) {
            $criteria['user'] = $user;
        }

        return $this->repository->findOneBy($criteria);
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

    public function loadTopLabels(\g5\AccountBundle\Entity\User $user, $limit = 5)
    {
        return $this->findLabelsBy(array('user' => $user), array('movie_count' => 'DESC'), $limit);
    }
}
