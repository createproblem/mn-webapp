<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace g5\HomeBundle\Tests\Twig\Extension;

use g5\HomeBundle\Twig\Extension\JSExtension;

require_once dirname(__DIR__).'/../../../../../app/KernelAwareTest.php';

class JSExportTest extends \KernelAwareTest
{
    public function testGetFunctions()
    {
        $container = $this->container->get('service_container');
        $jsExport = new JSExtension($container);

        $functions = $jsExport->getFunctions();

        $this->assertArrayHasKey('toJS', $functions);
    }

    public function testToJS()
    {
        $expected = '<script type="text/javascript">var test = {"test":1};</script>';

        $container = $this->container->get('service_container');
        $jsExport = new JSExtension($container);

        $compare = $jsExport->toJS('test', json_encode(array('test' => 1)));

        $this->assertEquals($expected, $compare);
    }

    public function testGetName()
    {
        $container = $this->container->get('service_container');
        $jsExport = new JSExtension($container);

        $this->assertEquals('g5_js_extension', $jsExport->getName());
    }
}
