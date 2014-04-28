<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Components\Api;

use Guzzle\Service\Client as BaseClient;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use g5\TmdbBundle\Logger\TmdbLogger;

class TmdbApiClient extends BaseClient
{
    /**
     * @var array
     */
    private static $configuration;

    /**
     * @var TmdbLogger
     */
    private $logger;

    /**
     * @{inheritDoc}
     */
    public static function factory($config = array())
    {
        // Default data
        $default = array('base_url' => 'https://api.themoviedb.org/3');

        // Required values
        $required = array(
            'base_url',
            'api_key'
        );

        // Merge default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        // create new client
        $client = new self($config->get('base_url'), $config);

        // Set the service description
        $configFile = dirname(__FILE__).'/../../Resources/config/tmdb.sdl.json';
        $client->setDescription(ServiceDescription::factory($configFile));

        // Add api_key to each request
        $client->getEventDispatcher()->addListener('client.create_request', function(\Guzzle\Common\Event $e) use ($config) {
            $e['request']->getQuery()->set('api_key', $config['api_key']);
        });

        return $client;
    }

    /**
     * @param TmdbLogger $logger
     */
    public function setLogger(TmdbLogger $logger)
    {
        $this->logger = $logger;
        $this->getEventDispatcher()->addListener('command.after_send', function(\Guzzle\Common\Event $e) use ($logger) {
            $request = $e['command']->getRequest();
            $method = $request->getMethod();
            $url = $request->getUrl();
            $scheme = $request->getScheme();
            $path = str_replace('/3', '', $request->getPath());
            $query = $request->getQuery();
            $time = $e['command']->getResponse()->getInfo()['total_time'];
            unset($query['api_key']);

            $logger->logQuery($url, $time, $path, $method, $scheme, $query->toArray());
        });
    }

    public function getImageBaseUrl($size)
    {
        $this->loadConfiguration();

        return self::$configuration['images']['base_url'].$size;
    }

    /**
     * @return array
     */
    public function loadConfiguration()
    {
        if (null === self::$configuration) {
            self::$configuration = $this->getConfiguration();
        }

        return self::$configuration;
    }
}
