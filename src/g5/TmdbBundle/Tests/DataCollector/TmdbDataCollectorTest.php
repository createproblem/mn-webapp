<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Tests\DataCollector;

use g5\TmdbBundle\DataCollector\TmdbDataCollector;
use g5\TmdbBundle\Logger\TmdbLogger;

class TmdbDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectQueryCount()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);
        $dataCollector = new TmdbDataCollector($logger);

        $total = rand(1, 15);
        for ($i = 0; $i < $total; $i++) {
            $logger->logQuery('testurl', 0.1, 'testPath', 'testMethod', 'testScheme', array());
        }

        $dataCollector->collect($this->getMockRequest(), $this->getMockResponse());
        $this->assertEquals($total, $dataCollector->getQueryCount());
    }

    public function testCorrectTotalTimeCalculation()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);
        $dataCollector = new TmdbDataCollector($logger);

        $logger->logQuery('testurl', 0.1, 'testPath', 'testMethod', 'testScheme', array());
        $logger->logQuery('testurl', 0.1, 'testPath', 'testMethod', 'testScheme', array());

        $dataCollector->collect($this->getMockRequest(), $this->getMockResponse());
        $this->assertEquals(0.2, $dataCollector->getTotalTime());
    }

    public function testCorrectlyFormattedQueryReturned()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);
        $dataCollector = new TmdbDataCollector($logger);

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
        $dataCollector->collect($this->getMockRequest(), $this->getMockResponse());

        $comapre = $dataCollector->getQueries();
        $this->assertEquals($comapre[0], $expected);
    }

    public function testGetName()
    {
        $logger = new TmdbLogger($this->getMockLogger(), true);
        $dataCollector = new TmdbDataCollector($logger);

        $this->assertEquals('tmdb', $dataCollector->getName());
    }

    private function getMockRequest()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Request');
    }

    private function getMockResponse()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Response');
    }

    private function getMockLogger()
    {
        return $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
