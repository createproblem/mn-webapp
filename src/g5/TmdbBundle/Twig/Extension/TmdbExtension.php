<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\TmdbBundle\Twig\Extension;

use g5\TmdbBundle\Components\Api\TmdbApiClient;

class TmdbExtension extends \Twig_Extension
{
    private $tmdbApiClient;

    /**
     * Consturctor.
     *
     * @param TmdbApiClient $tmdbApiClient
     */
    public function __construct(TmdbApiClient $tmdbApiClient)
    {
        $this->tmdbApiClient = $tmdbApiClient;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('img_backdrop_w300', array($this, 'imageBackdropW300Filter')),
            new \Twig_SimpleFilter('img_backdrop_w780', array($this, 'imageBackdropW780Filter')),
            new \Twig_SimpleFilter('img_backdrop_w1280', array($this, 'imageBackdropW1280Filter')),
            new \Twig_SimpleFilter('img_backdrop_original', array($this, 'imageBackdropOriginalFilter')),
            new \Twig_SimpleFilter('img_poster_w185', array($this, 'imagePosterW185Filter')),
        );
    }

    /**
     * Executes all allowed filter methods.
     *
     * @param  string $method
     * @param  array  $arguments
     *
     * @return string
     */
    public function __call($method, $arguments)
    {
        $filter = $this->convertFunctionNameToFilterName($method);

        if (!in_array($filter, $this->getFilterNames())) {
            throw new \BadMethodCallException("Filter '{$filter}' does not exist.");
        }

        if (count($arguments) !== 1) {
            throw new \InvalidArgumentException("Filter '{$filter}' allows only one argument as string.");
        }

        return $this->getImageBaseUrl($method, $arguments[0]);
    }

    /**
     * Get the base url.
     *
     * @param  string $function
     * @param  string $suffix
     *
     * @return string
     */
    private function getImageBaseUrl($function, $suffix)
    {
        $url = $this->tmdbApiClient->getImageBaseUrl($this->convertFunctionNameToConstant($function));
        $url .= $suffix;

        return $url;
    }

    /**
     * Converts a function name to a g5TmdbSettings constant for image resolutions.
     * Example: imageBackdropW300Filter => g5\TmdbBundle\g5TmdbSettings::IMAGE_BACKDROP_W300
     *
     * @param  string $function
     *
     * @return string
     */
    private function convertFunctionNameToConstant($function)
    {
        $parts = preg_split('/(?=[A-Z])/', $function);
        array_pop($parts);
        $const = strtoupper(implode('_', $parts));

        $constant = constant('g5\TmdbBundle\g5TmdbSettings::'.$const);

        return $constant;
    }

    /**
     * Converts a function name to a filter name.
     * Example: imageBackdropW300Filter => img_backdrop_w300
     *
     * @param  string $name [description]
     *
     * @return string
     */
    private function convertFunctionNameToFilterName($name)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $name, $matches);

        $ret = $matches[0];
        array_pop($ret);

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return str_replace('image', 'img', implode('_', $ret));
    }

    /**
     * Extract all filter names from all filters.
     *
     * @return array
     */
    private function getFilterNames()
    {
        $names = array();
        foreach ($this->getFilters() as $filter) {
            $names[] = $filter->getName();
        }

        return $names;
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'tmdb_extension';
    }
}
