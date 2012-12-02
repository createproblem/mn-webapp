<?php
// src/g5/MovieBundle/Tmdb/Tmdb.php
namespace g5\MovieBundle;

define('TMDB_DOMAIN', 'api.themoviedb.org');
define('TMDB_VERSION', '/3');

define('API_CONFIGURATION', '/configuration');
define('API_SEARCH_MOVIE', '/search/movie');
define('API_MOVIE', '/movie');

class Tmdb
{
    const POSTER_SIZE_w154  = 'w154';
    const POSTER_SIZE_w185  = 'w185';
    const POSTER_SIZE_w92   = 'w92';
    const POSTER_SIZE_w500  = 'w500';
    const POSTER_SIZE_w342  = 'w342';
    
    private $_apiKey;
    private $_lastUrl;
    
    private static $_configuration = null;
    
    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
        if (!is_object(self::$_configuration != null)) {
            self::$_configuration = $this->_loadConfiguration();
        }
    }
    
    public function getApiKey()
    {
        return $this->_apiKey;
    }
    
    public function getLastRequestUrl()
    {
        return $this->_lastUrl;
    }
    
    public function _buildUrl($service, array $params = array())
    {
        $url = 'http://' . TMDB_DOMAIN . TMDB_VERSION . $service . '?api_key=' . $this->getApiKey();
        foreach ($params as $var => $val) {
            $url .= '&' . $var . '=' . urlencode($val);
        }
        
        return $url;
    }
    
    public function getImageUrl($size)
    {
        $url = self::$_configuration->images->base_url . $size;
        return $url;
    }
    
    public function getMovieData($id)
    {
        $url = $this->_buildUrl(API_MOVIE . '/' . $id);
        
        return $this->parse($this->_request($url));
    }
    
    public function _request($url)
    {
        $this->_lastUrl = $url;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
    
    public function _loadConfiguration()
    {
        $url = $this->_buildUrl(API_CONFIGURATION);
        
        return $this->parse($this->_request($url));
    }
    
    public function searchMovie($query, $year = null)
    {
        $params['query'] = $query;
        if ($year != null) {
            $params['year'] = $year;
        }
        $url = $this->_buildUrl(API_SEARCH_MOVIE, $params);
        
        $result = $this->parse($this->_request($url));
        /*if (!is_object($result)) {
            $result = new \StdClass();
        }*/
        return $result;
    }
    
    public function parse($response)
    {
        return json_decode($response);
    }
    
    
}

?>
