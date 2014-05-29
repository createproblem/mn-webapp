<?php

namespace g5\OAuthServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class g5OAuthServerBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
