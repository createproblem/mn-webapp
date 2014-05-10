<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\MovieBundle\Tests\Form\Handler;

use g5\MovieBundle\Form\Handler\LinkFormHandler;
use g5\MovieBundle\Form\Model\Link;

require_once dirname(__DIR__).'/../../../../../app/KernelAwareTest.php';

class LinkFormHandlerTest extends \KernelAwareTest
{
    private $lm;
    private $mm;
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->mm = $this->get('g5_movie.movie_manager');
        $this->lm = $this->get('g5_movie.label_manager');
        $normalizer = $this->get('g5.normalizer');

        $this->handler = new LinkFormHandler($this->lm, $this->mm, $normalizer);
    }

    public function testProcessNewAndRemoveLabel()
    {
        $form = $this->getMockForm();
        $user = $this->helper->loadUser('test');

        $movie = $user->getMovies()[0];
        $expected = array(uniqid());

        $link = $this->getLink($movie->getId(), join(',', $expected));

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $labels = $this->handler->process($form, $user);
        foreach ($expected as $name) {
            $this->assertTrue(in_array($name, $labels));
        }
    }

    public function testProcessLinkExisting()
    {
        $form = $this->getMockForm();
        $user = $this->helper->loadUser('test');

        $movie = $user->getMovies()[0];
        $expected = array('top-hits');

        $link = $this->getLink($movie->getId(), join(',', $expected));

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $labels = $this->handler->process($form, $user);
        foreach ($expected as $name) {
            $this->assertTrue(in_array($name, $labels));
        }
    }

    public function testProcessMovieNotFound()
    {
        $form = $this->getMockForm();
        $user = $this->helper->loadUser('test');

        $link = $this->getLink(9999, join(',', array(uniqid())));

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $labels = $this->handler->process($form, $user);
        $this->assertFalse($labels);
    }

    private function getMockForm()
    {
        return $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getLink($movieId, $labelName)
    {
        $link = new Link();
        $link->setMovieId($movieId);
        $link->setName($labelName);

        return $link;
    }
}
