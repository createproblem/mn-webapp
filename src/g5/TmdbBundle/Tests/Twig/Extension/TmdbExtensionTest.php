<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Tests\Twig\Extension;

use g5\TmdbBundle\Twig\Extension\TmdbExtension;

class TmdbExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function backdropSizes()
    {
        return array(
            array('w300'),
            array('w780'),
            array('w1280'),
            array('original')
        );
    }

    public function posterSizes()
    {
        return array(
            array('w185')
        );
    }

    /**
     * @dataProvider backdropSizes
     */
    public function testCorrectBackdropImageUrls($size)
    {
        $apiMock = $this->getMockTmdbApiWithImageUrl($size);
        $extension = new TmdbExtension($apiMock);

        $expected = 'http://localhost/'.$size.'/test.jpg';
        $function = 'imageBackdrop'.ucfirst($size).'Filter';

        $compare = call_user_func(array($extension, $function), '/test.jpg');

        $this->assertEquals($expected, $compare);
    }

    /**
     * @dataProvider posterSizes
     */
    public function testCorrectPosterImageUrls($size)
    {
        $apiMock = $this->getMockTmdbApiWithImageUrl($size);
        $extension = new TmdbExtension($apiMock);

        $expected = 'http://localhost/'.$size.'/test.jpg';
        $function = 'imagePoster'.ucfirst($size).'Filter';

        $compare = call_user_func(array($extension, $function), '/test.jpg');

        $this->assertEquals($expected, $compare);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Filter 'img_backdrop_w300' allows only one argument as string.
     */
    public function testInvalidArgumentException()
    {
        $extension = new TmdbExtension($this->getMockTmdbApi());
        $extension->imageBackdropW300Filter('123', 'asf');
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Filter 'img_backdrop_w3100' does not exist.
     */
    public function testBadMethodCallException()
    {
        $extension = new TmdbExtension($this->getMockTmdbApi());
        $extension->imageBackdropW3100Filter('123');
    }

    public function testGetFilters()
    {
        $extension = new TmdbExtension($this->getMockTmdbApi());
        $this->assertTrue(is_array($extension->getFilters()));
    }

    public function testGetName()
    {
        $extension = new TmdbExtension($this->getMockTmdbApi());

        $this->assertEquals('tmdb_extension', $extension->getName());
    }

    private function getMockTmdbApiWithImageUrl($size)
    {
        $mock = $this->getMockBuilder('g5\TmdbBundle\Components\Api\TmdbApiClient')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $mock->expects($this->once())
            ->method('getImageBaseUrl')
            ->with($this->equalTo($size))
            ->will($this->returnValue('http://localhost/'.$size))
        ;

        return $mock;
    }

    private function getMockTmdbApi()
    {
        return $this->getMockBuilder('g5\TmdbBundle\Components\Api\TmdbApiClient')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
