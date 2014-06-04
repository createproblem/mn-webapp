<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\AccountBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class UserApiControllerTest extends \g5WebTestCase
{
    public function testMeAction()
    {
        $um = $this->get('fos_user.user_manager');
        $serializer = $this->get('jms_serializer');
        $user = $um->findUserBy(array('email' => 'test@example.com'));

        $token = uniqid();
        $this->loginOauth('test@example.com', $token);

        $this->client->request('GET', '/api/me.json', array('access_token' => $token));

        $userS = $serializer->serialize($user, 'json');

        $this->assertEquals($userS, $this->client->getResponse()->getContent());
    }
}
