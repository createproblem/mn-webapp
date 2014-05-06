<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Logger;

use Psr\Log\LoggerInterface;

class TmdbLogger implements LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * Constructor.
     *
     * @param LoggerInterface|null  $logger The Symfony Logger
     * @param boolean               $debug
     */
    public function __construct(LoggerInterface $logger, $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * @param  string $url
     * @param  double $time
     * @param  string $path
     * @param  string $method
     * @param  string $scheme
     * @param  array  $data
     */
    public function logQuery($url, $time, $path, $method, $scheme, array $data = array())
    {
        if ($this->debug) {
            $this->queries[] = array(
                'url' => $url,
                'time' => $time,
                'data' => $data,
                'path' => $path,
                'method' => $method,
                'scheme' => $scheme,
            );
        }

        $message = sprintf("%s %0.2f ms", $url, $time * 1000);
        $this->logger->info($message);
    }

    /**
     * @return integer
     */
    public function getQueryCount()
    {
        return count($this->queries);
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @{inheritDoc}
     */
    public function emergency($message, array $context = array())
    {
        return $this->logger->emergency($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function alert($message, array $context = array())
    {
        return $this->logger->alert($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function critical($message, array $context = array())
    {
        return $this->logger->critical($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function error($message, array $context = array())
    {
        return $this->logger->error($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function warning($message, array $context = array())
    {
        return $this->logger->warning($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function notice($message, array $context = array())
    {
        return $this->logger->notice($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function info($message, array $context = array())
    {
        return $this->logger->info($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function debug($message, array $context = array())
    {
        return $this->logger->debug($message, $context);
    }

    /**
     * @{inheritDoc}
     */
    public function log($level, $message, array $context = array())
    {
        return $this->logger->log($level, $message, $context);
    }
}
