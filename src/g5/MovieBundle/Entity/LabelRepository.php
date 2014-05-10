<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use g5\AccountBundle\Entity\User;

/**
 * LabelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LabelRepository extends EntityRepository
{
    /**
     * @param  string                        $name
     * @param  \g5\AccountBundle\Entity\User $user
     *
     * @return array
     */
    public function findByNameWithLike($name, \g5\AccountBundle\Entity\User $user)
    {
        $parameters = array();

        $qb = $this->createQueryBuilder('l');
        $qb = $qb->where($qb->expr()->like('l.name', ':name'))
            ->andwhere('l.user = :user')
            ->setParameters(array(
                ':name' => $name.'%',
                ':user' => $user,
            ))
            ->getQuery();

        return $qb->getResult();
    }

    /**
     * @param  array  $names
     * @param  User   $user
     *
     * @return array
     */
    public function findByNamesNorm(array $names, User $user)
    {
        $qb = $this->createQueryBuilder('l');

        $ors = array();
        foreach ($names as $value) {
            $ors[] = $qb->expr()->eq('l.name_norm', $qb->expr()->literal($value));
        }

        $qb = $qb->andwhere(join(' OR ', $ors))
            ->andwhere($qb->expr()->eq('l.user', $qb->expr()->literal($user->getId())))
            ->getQuery()
        ;

        return $qb->getResult();
    }

    /**
     * @param  integer   $id
     * @param  User|null $user
     *
     * @return Label
     */
    public function find($id, User $user = null)
    {
        $criteria['id'] = $id;

        if (null !== $user) {
            $criteria['user'] = $user;
        }

        return $this->findOneBy($criteria);
    }
}
