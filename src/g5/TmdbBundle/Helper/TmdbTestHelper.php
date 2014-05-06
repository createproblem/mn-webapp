<?php

namespace g5\TmdbBundle\Helper;

class TmdbTestHelper
{
    private $fixtureDir;

    public function __construct($fixtureDir)
    {
        $this->fixtureDir = $fixtureDir;
    }

    public function getFixture($file)
    {
        return file_get_contents($this->fixtureDir.'/'.$file);
    }
}
