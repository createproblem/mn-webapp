<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class ToolsControllerTest extends \g5WebTestCase
{
    public function testIndexAction()
    {
        $this->login($this->client);

        $crawler = $this->client->request('GET', '/movie/tools');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
