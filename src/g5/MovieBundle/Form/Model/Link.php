<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Form\Model;

class Link
{
    /**
     * @var int
     */
    private $movieId;

    /** @var string
     */
    private $name;

    /**
     * Gets the value of movieId.
     *
     * @return int
     */
    public function getMovieId()
    {
        return $this->movieId;
    }

    /**
     * Sets the value of movieId.
     *
     * @param int $movieId the movie id
     */
    public function setMovieId($movieId)
    {
        $this->movieId = $movieId;
    }

    /**
     * Gets the value of name.
     *
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param int $name the name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
