<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/g5WebTestCase.php';

class LabelControllerTest extends \g5WebTestCase
{
    public function testNewAction()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/movie/label/new');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('#g5movie_label_new_form')->count());
    }

    public function testFindAction()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/movie/label/find',
            array('query' => 'horr'),
            array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
