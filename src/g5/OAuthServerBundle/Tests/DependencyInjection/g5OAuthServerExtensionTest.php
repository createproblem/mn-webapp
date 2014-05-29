<?php

/**
 * This file is part of the mn.io package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\OAuthServerBundle\Tests\DependencyInjection;

require_once dirname(__DIR__).'/../../../../app/KernelAwareTest.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use g5\OAuthServerBundle\DependencyInjection\g5OAuthServerExtension;

class g5OAuthServerExtensionTest extends \KernelAwareTest
{
    public function setUp()
    {
        parent::setUp();

        if (!class_exists('Symfony\Component\DependencyInjection\ContainerBuilder')) {
            $this->markTestSkipped('The DependencyInjection component is not available.');
        }
    }

    public function testLoadSetups()
    {
        $container = $this->load(array(array()));
        $authorizeFormType = $container->get('g5_oauth_server.authorize.form_type');
        $this->assertInstanceOf('g5\OAuthServerBundle\Form\Type\AuthorizeFormType', $authorizeFormType);
    }

    private function load(array $configs)
    {
        $container = new ContainerBuilder();
        $extension = new g5OAuthServerExtension();
        $extension->load($configs, $container);

        return $container;
    }
}
