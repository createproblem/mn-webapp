<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Tests\Logger;

use g5\TmdbBundle\Logger\TmdbLogger;

class TmdbLoggerTest extends \PHPUnit_Framework_TestCase
{
    private function getMockLogger()
    {
        return $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMockLoggerForLevelMessageAndContext($level, $message, $context)
    {
        $loggerMock = $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $loggerMock->expects($this->once())
            ->method($level)
            ->with(
                $this->equalTo($message),
                $this->equalTo($context)
            );

        $tmdbLogger = new TmdbLogger($loggerMock);

        return $tmdbLogger;
    }

    public function testZeroQueryCount()
    {
        $logger = new TmdbLogger($this->getMockLogger());
        $this->assertEquals(0, $logger->getQueryCount());
    }

    public function testCorrectQueryCount()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);

        $total = rand(1, 15);
        for ($i = 0; $i < $total; $i++) {
            $logger->logQuery('testurl', 0.1, 'testPath', 'testMethod', 'testScheme', array());
        }

        $this->assertEquals($total, $logger->getQueryCount());
    }

    public function testQueryiesStoredOnDebugFalse()
    {
        $logger = new TmdbLogger($this->getMockLogger(), false);

        $total = rand(1, 15);
        for ($i = 0; $i < $total; $i++) {
            $logger->logQuery('testurl', 0.1, 'testPath', 'testMethod', 'testScheme', array());
        }

        $this->assertEquals(0, $logger->getQueryCount());
    }

    public function testCorrectlyFormattedQueryReturned()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);

        $url = 'testUrl';
        $time = 0.5;
        $path = 'testPath';
        $method = 'testMethod';
        $scheme = 'testScheme';
        $data = array();

        $expected = array(
            'url' => 'testUrl',
            'time' => 0.5,
            'path' => 'testPath',
            'method' => 'testMethod',
            'scheme' => 'testScheme',
            'data' => array(),
        );

        $logger->logQuery($url, $time, $path, $method, $scheme, $data);
        $comapre = $logger->getQueries();
        $this->assertEquals($comapre[0], $expected);
    }

    public function testQueryIsLogged()
    {
        $loggerMock = $this->getMockLogger();

        $logger = new TmdbLogger($loggerMock);

        $url = 'testUrl';
        $time = 12;
        $path = 'testPath';
        $method = 'testMethod';
        $scheme = 'testScheme';
        $data = array();

        $expectedMessage = 'testUrl 12000.00 ms';

        $loggerMock->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo($expectedMessage),
                $this->equalTo($data)
            );

        $logger->logQuery($url, $time, $path, $method, $scheme, $data);
    }

    public function logLevels()
    {
        return array(
            array('emergency'),
            array('alert'),
            array('critical'),
            array('error'),
            array('warning'),
            array('notice'),
            array('info'),
            array('debug'),
        );
    }

    /**
     * @dataProvider logLevels
     */
    public function testMessagesCanBeLoggedAtSpecificLogLevels($level)
    {
        $message = 'foo';
        $context = array('data');

        $loggerMock = $this->getMockLoggerForLevelMessageAndContext($level, $message, $context);

        call_user_func(array($loggerMock, $level), $message, $context);
    }

    public function testMessagesCanBeLoggedToArbitraryLevels()
    {
        $loggerMock = $this->getMockLogger();

        $level = 'info';
        $message = 'foo';
        $context = array('data');

        $loggerMock->expects($this->once())
            ->method('log')
            ->with(
                $level,
                $message,
                $context
            );

        $logger = new TmdbLogger($loggerMock);

        $logger->log($level, $message, $context);
    }
}
