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

abstract class MovieAbstract
{
    /**
     * Shortcut function
     *
     * @return array
     */
    public function getLabels()
    {
        $labels = array();

        foreach ($this->getMovieLabels() as $movieLabel) {
            $labels[] = $movieLabel->getLabel();
        }

        return $labels;
    }

    // /**
    //  * Shortcut function
    //  *
    //  * @param \g5\MovieBundle\Entity\Label $label
    //  */
    // public function addLabel(\g5\MovieBundle\Entity\Label $label)
    // {
    //     $movieLabel = new MovieLabel();
    //     $movieLabel->setMovie($this);
    //     $movieLabel->setLabel($label);

    //     $this->addMovieLabel($movieLabel);
    // }

    /**
     * Shortcut function
     *
     * @param  \g5\MovieBundle\Entity\Label $label
     */
    public function removeLabel(\g5\MovieBundle\Entity\Label $label)
    {
        foreach ($this->getMovieLabels() as $movieLabel) {
            if ($label->getId() === $movieLabel->getLabel()->getId()) {
                $this->removeMovieLabel($movieLabel);
            }
        }
    }
}
