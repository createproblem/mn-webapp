<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\DataCollector;

use g5\TmdbBundle\Logger\TmdbLogger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TmdbDataCollector extends DataCollector
{
    /**
     * @var TmdbLogger
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param TmdbLogger $logger
     */
    public function __construct(TmdbLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @{inheritDoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['query_count'] = $this->logger->getQueryCount();
        $this->data['queries'] = $this->logger->getQueries();
    }

    /**
     * @return array
     */
    public function getQueryCount()
    {
        return $this->data['query_count'];
    }

    /**
     * @return float
     */
    public function getTotalTime()
    {
        $time = 0;
        foreach ($this->data['queries'] as $query) {
            $time += $query['time'];
        }

        return $time;
    }

    /**
     * @return integer
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'tmdb';
    }

}
