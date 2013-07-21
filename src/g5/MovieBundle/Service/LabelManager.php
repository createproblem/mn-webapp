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
use g5\MovieBundle\Entity\Label;

class LabelManager
{
    private $em;

    private $repository;

    public function __construct($em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository('g5MovieBundle:Label');
    }

    public function findLabelNamesTypeahead($name, User $user)
    {
        $labels = $this->repository->findLikeByName($name, $user);

        return $labels;
    }

    public function createLabel()
    {
        return new Label();
    }

    public function update(Label $label)
    {
        if (null === $label->getId()) {
            $tLabel = $this->repository->findOneBy(array(
                'name' => $label->getName(),
                'user' => $label->getUser(),
            ));

            if (!$tLabel) {
                $this->em->persist($label);
            } else {
                $tLabel->setName($label->getName());
                foreach ($label->getMovies() as $movie) {
                    $tLabel->addMovie($movie);
                }
                $label = $tLabel;
            }
        }

        $this->em->flush();

        return $label;
    }

    public function findLabelById($id, \g5\AccountBundle\Entity\User $user = null)
    {
        return $this->repository->findOneBy(array('id' => $id, 'user' => $user));
    }
}
