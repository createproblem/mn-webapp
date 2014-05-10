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
            '/label/api/labels',
            array('q' => 'h')
        );

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals($content, '[{"id":1,"name":"Horror","name_norm":"horror"}]');
    }

    public function testDeleteLabelsAction()
    {
        $this->login($this->client);
        $lm = $this->get('g5_movie.label_manager');
        $user = $this->helper->loadUser('test');

        $label = $lm->createLabel();
        $label->setName(uniqid());
        $label->setNameNorm($label->getName());
        $label->setUser($user);
        $lm->updateLabel($label);

        $this->client->request(
            'DELETE',
            '/label/api/labels?unused=true'
            // array('unused' => true)
        );

        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertGreaterThanOrEqual(1, $content['labels_deleted']);
    }
}
