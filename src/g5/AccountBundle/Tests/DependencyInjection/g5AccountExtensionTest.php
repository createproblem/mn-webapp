<?php

/*
* This file is part of the mn-webapp package.
*
* (c) gogol-medien <https://github.com/gogol-medien/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\AccountBundle\Tests\DependencyInjection;

use g5\AccountBundle\DependencyInjection\g5AccountExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class g5AccountExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Symfony\Component\DependencyInjection\ContainerBuilder')) {
            $this->markTestSkipped('The DependencyInjection component is not available.');
        }
    }

    public function testLoadSetups()
    {
        $container = $this->load(array(array()));

        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $container);
    }

    private function load(array $configs)
    {
        $container = new ContainerBuilder();
        $extension = new g5AccountExtension();
        $extension->load($configs, $container);

        return $container;
    }
}
