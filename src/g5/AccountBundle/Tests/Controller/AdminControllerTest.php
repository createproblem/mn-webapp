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

class AdminControllerTest extends \g5WebTestCase
{
    public function testIndexAction()
    {
        $this->login($this->client);

        $crawler = $this->client->request('GET', '/account/admin/user');

        $this->assertEquals(1, $crawler->filter('table')->count());
    }

    public function testGenerateTokenActionNotFoundException()
    {
    	$this->login($this->client);
    	$this->client->request('GET', '/account/admin/user/generate-token', array('user_id' => 1));

    	$this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testGenerateToken()
    {
    	$um = $this->get('fos_user.user_manager');
    	$user = $um->findUserBy(array('email' => 'test@example.com'));

    	$this->login($this->client);
    	$this->client->request('GET', '/account/admin/user/generate-token', array('user_id' => $user->getId()));

    	$content = json_decode($this->client->getResponse()->getContent(), true);

    	$this->assertArrayHasKey('access_token', $content);
    }
}
