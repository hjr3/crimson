<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Url
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson;

/**
 * A set of utilities to for dealing with URL's.
 */
class Url
{
    /**
     * Creates a lambda function that prefixes a relative url with the specified host
     * 
     * @param string $host Host to prefix the relative url with
     * @return function Returns a function to create the base url with the specified host
     */
    public static function absolute($host) 
    {
        return function($path) use ($host) {
            return "{$host}{$path}";
        };
    }

    /**
     * Creates a function to rotate through a list of subdomains.
     * 
     * The most common use of this function is to distrubute the loading of
     * assets from a CDN over multiple URL's to speed up the time it takes for
     * a web browser to fetch all the assets.
     *
     * @param string $subdomain The subdomain part to use.
     * @param string $host The host name (without any subdomain).
     * @param int $rotations The max number of rotations to go through before
     *  starting over.
     * @param string $protocol Specify an optional protocol.
     * @static
     * @return function returns a function with to create the url
     */
    public static function rotate($subdomain, $host, $rotations, $protocol='http')
    {
        $current = 0;

        return function($path) use (&$current, $subdomain, $host, $rotations, $protocol) {
            if ($current == $rotations) {
                $current = 1;
            } else {
                $current++;
            }

            return "{$protocol}://{$subdomain}{$current}.{$host}{$path}";
        };
    }

    /**
     * Create a function to append a version query string to a url
     * 
     * @param string $host Host to prefix the relative url
     * @param string $version Version to append to the relative url
     * @return function
     */
    public static function version($host, $version)
    {
        return function($path) use ($host, $version) {
            return "{$host}{$path}?{$version}";
        };
    }
}
