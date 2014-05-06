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

    /**
     * Shortcut function
     *
     * @param \g5\MovieBundle\Entity\Label $label
     */
    public function addLabel(\g5\MovieBundle\Entity\Label $label)
    {
        $label->setMovieCount($label->getMovieCount() + 1);
        $this->setLabelCount($this->getLabelCount() + 1);

        $movieLabel = new MovieLabel();
        $movieLabel->setMovie($this);
        $movieLabel->setLabel($label);

        $this->addMovieLabel($movieLabel);

        return $movieLabel;
    }

    /**
     * Shortcut function will only add the if it doesn't exist
     *
     * @param \g5\MovieBundle\Entity\Label $label
     *
     * @return  boolean
     */
    public function addLabelSafe(\g5\MovieBundle\Entity\Label $label)
    {
        if (!$this->hasLabel($label)) {
            $this->addLabel($label);

            return true;
        }

        return false;
    }

    /**
     * Shortcut function
     *
     * @param  \g5\MovieBundle\Entity\Label $label
     */
    public function removeLabel(\g5\MovieBundle\Entity\Label $label)
    {
        foreach ($this->getMovieLabels() as $movieLabel) {
            if ($label->getId() === $movieLabel->getLabel()->getId()) {
                $label->setMovieCount($label->getMovieCount() - 1);
                $this->setLabelCount($this->getLabelCount() - 1);
                $this->removeMovieLabel($movieLabel);
            }
        }
    }

    /**
     * @param  g5MovieBundleEntityLabel $label
     *
     * @return boolean
     */
    public function hasLabel(\g5\MovieBundle\Entity\Label $label)
    {
        foreach ($this->getMovieLabels() as $movieLabel) {
            if ($label->getId() === $movieLabel->getLabel()->getId()) {
                return true;
            }
        }

        return false;
    }
}
