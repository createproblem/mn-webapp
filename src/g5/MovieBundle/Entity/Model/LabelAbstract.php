<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Entity\Model;

use g5\MovieBundle\Entity\MovieLabel;

abstract class LabelAbstract
{
    /**
     * Shortcut function
     *
     * @return array
     */
    public function getMovies()
    {
        $movies = array();

        foreach ($this->getMovieLabels() as $movieLabel) {
            $movies[] = $movieLabel->getMovie();
        }

        return $movies;
    }

    /**
     * Shortcut function
     *
     * @param \g5\MovieBundle\Entity\Movie $movie
     */
    public function addMovie(\g5\MovieBundle\Entity\Movie $movie)
    {
        $movieLabel = new MovieLabel();
        $movieLabel->setMovie($movie);
        $movieLabel->setLabel($this);

        $this->addMovieLabel($movieLabel);
    }

    /**
     * Shortcut function
     *
     * @param  \g5\MovieBundle\Entity\Model $movie
     */
    public function removeMove(\g5\MovieBundle\Entity\Movie $movie)
    {
        foreach ($this->getMovieLabels() as $movieLabel) {
            if ($movie->getId() === $movieLabel->getMovie()->getId()) {
                $this->removeMovieLabel($movieLabel);
            }
        }
    }

}
