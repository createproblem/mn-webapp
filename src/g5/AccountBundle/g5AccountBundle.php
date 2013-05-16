<?php

namespace g5\AccountBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class g5AccountBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
