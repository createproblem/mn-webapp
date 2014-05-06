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

class LabelApiControllerTest extends \g5WebTestCase
{
    public function testGetLabelsAction()
    {
        $this->login($this->client);

        $this->client->request(
            'GET',
            '/label/api2/labels',
            array('q' => 'h')
        );

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals($content, '[{"id":1,"name":"Horror","name_norm":"horror"}]');
    }
}
