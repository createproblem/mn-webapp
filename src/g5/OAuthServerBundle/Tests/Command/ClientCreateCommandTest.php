<?php

/*
* This file is part of the portal-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\OAuthServerBundle\Tests\Command;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use g5\OAuthServerBundle\Command\ClientCreateCommand;

class ClientCreateCommandTest extends \KernelAwareTest
{
    public function testExecute()
    {
        $application = new Application($this->kernel);
        $application->add(new ClientCreateCommand());

        $command = $application->find('g5:oauth-server:client:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command,
            '--redirect-uri' => array('http://localhost:8000/'),
            '--grant-type' => array('token', 'authorization_code'),
            'name' => 'TestClient123',
        ));

        $expected = "/Added a new client with name/";
        $this->assertRegExp($expected, $commandTester->getDisplay());

        $cm = $this->get('fos_oauth_server.client_manager.default');
        $oAuthClient = $cm->findClientBy(array('name' => 'TestClient123'));

        $om = $this->get('doctrine_mongodb')->getManager();
        $om->remove($oAuthClient);
        $om->flush();
    }
}
