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

class TmdbApiClient extends BaseClient
{
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
}
