<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\HomeBundle\Tests\Components;

use g5\HomeBundle\Components\Normalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    private $normalizer;

    public function setUp()
    {
        parent::setUp();

        $this->normalizer = new Normalizer();
    }

    public function testNormalizeUtf8String()
    {
        $expected = 'hoss-raeo';
        $s = $this->normalizer->normalizeUtf8String('Hoß räo');
        $this->assertEquals($expected, $s);

        $expected = '';
        $s = $this->normalizer->normalizeUtf8String('');
        $this->assertEquals($expected, $s);
    }
}
