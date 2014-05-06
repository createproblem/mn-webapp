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
        $normalizer = $this->get('g5_tools.normalizer');

        $this->handler = new LinkFormHandler($this->lm, $this->mm, $normalizer);
    }

    public function testProcessNewLabel()
    {
        $form = $this->getMockForm();
        $user = $this->loadTestUser();

        $movie = $user->getMovies()[0];
        $name = uniqid();
        $link = $this->getLink($movie->getId(), $name);

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $label = $this->handler->process($form, $user);

        $this->assertEquals($name, $label->getName());

        return $name;
    }

    public function testProcessWrongMovie()
    {
        $user = $this->loadTestUser();

        $name = uniqid();
        $link = $this->getLink(4999, $name);

        $form = $this->getMockForm();
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $label = $this->handler->process($form, $user);
        $this->assertFalse($label);
    }

    /**
     * @depends testProcessNewLabel
     */
    public function testProcessDuplicateLabel($name)
    {
        $form = $this->getMockForm();
        $user = $this->loadTestUser();

        $movie = $user->getMovies()[0];
        $link = $this->getLink($movie->getId(), $name);

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $label = $this->handler->process($form, $user);

        $this->assertFalse($label);
        $this->assertEquals(array('error' => 'Label already assigned.'), $this->handler->getErrors());

        return $name;
    }

    /**
     * @depends testProcessDuplicateLabel
     */
    public function testProcessExistingLabel($name)
    {
        $form = $this->getMockForm();
        $user = $this->loadTestUser();

        $movie = $user->getMovies()[1];
        $link = $this->getLink($movie->getId(), $name);

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($link))
        ;

        $label = $this->handler->process($form, $user);

        $this->assertEquals($name, $label->getName());
        $this->lm->removeLabel($label);
    }

    public function testProcessNotValid()
    {
        $form = $this->getMockForm();
        $user = $this->loadTestUser();

        $movie = $user->getMovies()[0];
        $link = $this->getLink($movie->getId(), uniqid());

        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false))
        ;

        $label = $this->handler->process($form, $user);

        $this->assertFalse($label);
        $this->assertNull($this->handler->getErrors());
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
