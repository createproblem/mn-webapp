<?php

namespace g5\AccountBundle\Tests\Controller;

use g5\AccountBundle\Tests\AccountAwareWebTestCase;

class RegistrationControllerTest extends AccountAwareWebTestCase
{
    public function testRegister()
    {
        $this->addUser();
    }
}

