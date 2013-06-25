<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

require_once __DIR__.'/AppKernel.php';

class AppTestKernel extends AppKernel
{
    private $kernelModifier = null;

    public function boot()
    {
        parent::boot();

        if ($kernelModifier = $this->kernelModifier) {
            $kernelModifier($this);
            $this->kernelModifier = null;
        }
    }

    public function setKernelModifier(\Closure $kernelModifier)
    {
        $this->kernelModifier = $kernelModifier;

        // force shutdown
        $this->shutdown();
    }
}
