<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Flickr
 * @copyright 2011 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson\Services\Flickr;

/**
 * Simple interface to the Flickr REST API
 *
 * Flickr's REST api works more like RPC than REST.  Everything is a get
 * request to the same resource.  A list of methods is exposed that all
 * pretty much work the same way.  This class will translate the results
 * into a native php array (never any objects) so any consuming class
 * knows exactly how to handle the response.
 *
 * The last url requested is saved in the class instance so a the results
 * can be cached by the complete url requested.
 *
 */
class Api
{
    /**
     * The Flickr REST endpoint
     *
     * This entire class is built on the assumption that a REST endpoint is
     * being used.
     */
    const URL = 'http://api.flickr.com/services/rest/';

    /**
     * Flickr API key
     *
     * @var string
     */
    protected $api_key;

    /**
     * The last url requested
     *
     * This allows outside caches to easily get a key to cache the results.
     *
     * @var string
     */
    protected $last_url;

    /**
     * Constructor
     *
     * @param string $api_key Flickr API key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->last_url = '';
    }

    /**
     * Make a request to the Flickr API
     *
     * @param  string       $method Flickr method to call
     * @param  array        $params List of parameters specific to the Flickr method
     * @return string|false A json encoded response on success, else false.
     */
    public function request($method, array $params=array())
    {
        $url = $this->buildUrl($method, $params);

        $this->last_url = $url;

        $response = $this->send($url);

        if ($response) {
            $response = json_decode($response, true);

            if ($response['stat'] == 'fail') {
                $response = false;
            }
        } else {
            $response = false;
        }

        return $response;
    }

    /**
     * Glue together a url and list query parameters
     *
     * @param  string $method Flickr method to call
     * @param  array  $params List of parameters specific to the Flickr method
     * @return string The complete url
     */
    protected function buildUrl($method, array $params)
    {
        $query = array(
            'method' => $method,
            'api_key' => $this->api_key,
            'format' => 'json',
            'nojsoncallback' => 1,
        );

        $query += $params;

        $query = http_build_query($query);

        $url = self::URL . "?{$query}";

        return $url;
    }

    /**
     * Send a GET request via cUrl
     *
     * @param  string $url The url to make a request with
     * @return string cUrl response string
     */
    protected function send($url)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function getLastUrl()
    {
        return $this->last_url;
    }
}
