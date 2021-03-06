<?php

namespace g5\MovieBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends DocumentRepository
{
    /**
     * Returns unique movies
     *
     * @param  array  $criteria
     * @return mixed
     */
    public function findUniqueBy(array $criteria)
    {
        return $this->findBy(array('user.id' => $criteria['user'], 'tmdb_id' => $criteria['tmdb_id']));
    }

    public function findPaginated($user, $limit, $skip)
    {
        return $this->createQueryBuilder()
            ->field('user')->references($user)
            ->limit($limit)
            ->skip($skip)
            ->sort('created_at', 'ACS')
            ->getQuery()
            ->execute();
    }
}
