<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Nemcache
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: MemcachedBackendTest.php 17363 2009-08-03 07:40:18Z bkarwin $
 */

/**
 * @see Zend_Cache_Backend_Interface
 */
require_once 'Zend/Cache/Backend/ExtendedInterface.php';

/**
 * @see Zend_Cache_Backend
 */
require_once 'Zend/Cache/Backend.php';


class Crimson_Memcache extends Zend_Cache_Backend implements Zend_Cache_Backend_ExtendedInterface
{
    /**
     * Default Values
     */
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT =  11211;
    const DEFAULT_WEIGHT  = 0;

    /**
     * Log message
     */
    const TAGS_UNSUPPORTED_BY_CLEAN_OF_MEMCACHED_BACKEND = 'Crimson_Memcache::clean() : tags are unsupported by the Memcached backend';
    const TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND =  'Crimson_Memcache::save() : tags are unsupported by the Memcached backend';

    /**
     * Available options
     *
     * =====> (array) servers :
     * an array of memcached server ; each memcached server is described by an associative array :
     * 'host' => (string) : the name of the memcached server
     * 'port' => (int) : the port of the memcached server
     * 'weight' => (int) : number of buckets to create for this server which in turn control its
     *                     probability of it being selected. The probability is relative to the total
     *                     weight of all servers.
     *
     * =====> (boolean) compression :
     * true if you want to use on-the-fly compression
     *
     * =====> (string) persistent :
     * 'persistent' => (string) : the id to use for a persistent connection
     *
     * @var array available options
     */
    protected $_options = array(
        'servers' => array(array(
            'host' => self::DEFAULT_HOST,
            'port' => self::DEFAULT_PORT,
            'weight'  => self::DEFAULT_WEIGHT
        )),
        'compression' => false,
        'persistent' => false
    );

    /**
     * Memcache object
     *
     * @var mixed memcache object
     */
    protected $_memcache = null;

    /**
     * Constructor
     *
     * @param array $options associative array of options
     * @throws Zend_Cache_Exception
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!extension_loaded('memcached')) {
            Zend_Cache::throwException('The memcached extension must be loaded for using this backend !');
        }

        parent::__construct($options);

        if (isset($this->_options['servers'])) {
            $value = $this->_options['servers'];
            if (isset($value['host'])) {
                // in this case, $value seems to be a simple associative array (one server only)
                $value = array(0 => $value); // let's transform it into a classical array of associative arrays
            }
            $this->setOption('servers', $value);
        }

        if (isset($options['persistent']) && is_string($options['persistent'])) {
            $this->_memcache = new Memcached($options['persistent']);
        } else {
            $this->_memcache = new Memcached;
        }

        if (isset($options['compression']) && $options['compression'] === false) {
            $this->_memcache->setOption(Memcached::OPT_COMPRESSION, false);
        } else {
            $this->_memcache->setOption(Memcached::OPT_COMPRESSION, true);
        }

        // prevent adding duplicate servers if using a persistent connection
        if (count($this->_memcache->getServerList())) {
            return;
        }

        foreach ($this->_options['servers'] as $server) {
            if (!array_key_exists('host', $server)) {
                $server['host'] = self::DEFAULT_HOST;
            }
            if (!array_key_exists('port', $server)) {
                $server['port'] = self::DEFAULT_PORT;
            }
            if (!array_key_exists('weight', $server)) {
                $server['weight'] = self::DEFAULT_WEIGHT;
            }

            $this->_memcache->addServer($server);
        }
    }

    /**
     * Return the instance of memcache being used
     *
     * This allows an application to get the memcache instance via
     * $cache->getBackend()->getInstance().  The application can then
     * use the specific Memcached class methods, such as class.
     *
     * WARNING: Zend wraps their keys in an array with extra metadata.  Be
     * careful not to mix and match vanilla Memcache with Crimson_Memcache.
     */
    public function getCacheInstance()
    {
        return $this->_memcache;
    }

    /**
     * Test if a cache is available for the given id
     *
     * Note : It is possible to save a value of false to memcache.  The
     * identical operator (===) should be used when checking the return
     * value.
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return string|null cached datas
     */
    public function load($id, $doNotTestCacheValidity = false)
    {
        $tmp = $this->_memcache->get($id);
        if ($this->_memcache->getResultCode() === Memcached::RES_SUCCESS && is_array($tmp)) {
            return $tmp[0];
        }

        return null;
    }

    /**
     * Test if a cache is available for the given id
     *
     * @param  string $id Cache id
     * @return int|false modified timestamp if available; else false.
     */
    public function test($id)
    {
        $tmp = $this->_memcache->get($id);
        if ($this->_memcache->getResultCode() === Memcached::RES_SUCCESS && is_array($tmp)) {
            return $tmp[1];
        }

        return false;
    }

    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always "string" (serialization is done by the
     * core not by the backend)
     *
     * @param  string $data             Datas to cache
     * @param  string $id               Cache id
     * @param  array  $tags             Not supported; always specify array().
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean True if no problem
     */
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        $lifetime = $this->getLifetime($specificLifetime);

        $result = $this->_memcache->set($id, array($data, time(), $lifetime), $lifetime);

        if (count($tags) > 0) {
            $this->_log(self::TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND);
        }

        return $result;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
     */
    public function remove($id)
    {
        return $this->_memcache->delete($id);
    }

    /**
     * Clean some cache records
     *
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => unsupported
     * 'matchingTag'    => unsupported
     * 'notMatchingTag' => unsupported
     * 'matchingAnyTag' => unsupported
     *
     * @param  string $mode Clean mode
     * @param  array  $tags Array of tags
     * @throws Zend_Cache_Exception
     * @return boolean True if no problem
     */
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        switch ($mode) {
            case Zend_Cache::CLEANING_MODE_ALL:
                return $this->_memcache->flush();
                break;
            case Zend_Cache::CLEANING_MODE_OLD:
                $this->_log("Crimson_Memcached::clean() : CLEANING_MODE_OLD is unsupported by the Memcached backend");
                break;
            case Zend_Cache::CLEANING_MODE_MATCHING_TAG:
            case Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG:
            case Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG:
                $this->_log(self::TAGS_UNSUPPORTED_BY_CLEAN_OF_MEMCACHED_BACKEND);
                break;
            default:
                Zend_Cache::throwException('Invalid mode for clean() method');
                break;
        }
    }

    /**
     * Return true if the automatic cleaning is available for the backend
     *
     * @return boolean
     */
    public function isAutomaticCleaningAvailable()
    {
        return false;
    }

    /**
     * Set the frontend directives
     *
     * @param  array $directives Assoc of directives
     * @throws Zend_Cache_Exception
     * @return void
     */
    public function setDirectives($directives)
    {
        parent::setDirectives($directives);
        $lifetime = $this->getLifetime(false);
        if ($lifetime > 2592000) {
            // #ZF-3490 : For the memcached backend, there is a lifetime limit of 30 days (2592000 seconds)
            $this->_log('memcached backend has a limit of 30 days (2592000 seconds) for the lifetime');
        }
        if ($lifetime === null) {
            // #ZF-4614 : we tranform null to zero to get the maximal lifetime
            parent::setDirectives(array('lifetime' => 0));
        }
    }

    /**
     * Return an array of stored cache ids
     *
     * @return array array of stored cache ids (string)
     */
    public function getIds()
    {
        $this->_log("Crimson_Memcached::save() : getting the list of cache ids is unsupported by the Memcache backend");
        return array();
    }

    /**
     * Unsupported.
     *
     * @return array Empty array.
     */
    public function getTags()
    {
        $this->_log(self::TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND);
        return array();
    }

    /**
     * Unsupported.
     *
     * @return array Empty array.
     */
    public function getIdsMatchingTags($tags = array())
    {
        $this->_log(self::TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND);
        return array();
    }

    /**
     * Unsupported.
     *
     * @return array Empty array.
     */
    public function getIdsNotMatchingTags($tags = array())
    {
        $this->_log(self::TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND);
        return array();
    }

    /**
     * Unsupported.
     *
     * @return array Empty array.
     */
    public function getIdsMatchingAnyTags($tags = array())
    {
        $this->_log(self::TAGS_UNSUPPORTED_BY_SAVE_OF_MEMCACHED_BACKEND);
        return array();
    }

    /**
     * Return the filling percentage of the backend storage
     *
     * @throws Zend_Cache_Exception
     * @return int integer between 0 and 100
     */
    public function getFillingPercentage()
    {
        $mems = $this->_memcache->getStats();

        $memSize = 0;
        $memUsed = 0;
        foreach ($mems as $key => $mem) {
        	if ($mem === false) {
                Zend_Cache::throwException('can\'t get stat from ' . $key);
        	} else {
        		$eachSize = $mem['limit_maxbytes'];
        		if ($eachSize == 0) {
                    Zend_Cache::throwException('can\'t get memory size from ' . $key);
        		}

        		$eachUsed = $mem['bytes'];
		        if ($eachUsed > $eachSize) {
		            $eachUsed = $eachSize;
		        }

        		$memSize += $eachSize;
        		$memUsed += $eachUsed;
        	}
        }

        return ((int) (100. * ($memUsed / $memSize)));
    }

    /**
     * Return an array of metadatas for the given cache id
     *
     * The array must include these keys :
     * - expire : the expire timestamp
     * - tags : a string array of tags
     * - mtime : timestamp of last modification time
     *
     * @param string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
     */
    public function getMetadatas($id)
    {
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp)) {
            $data = $tmp[0];
            $mtime = $tmp[1];
            if (!isset($tmp[2])) {
                // because this record is only with 1.7 release
                // if old cache records are still there...
                return false;
            }
            $lifetime = $tmp[2];
            return array(
                'expire' => $mtime + $lifetime,
                'tags' => array(),
                'mtime' => $mtime
            );
        }
        return false;
    }

    /**
     * Give (if possible) extra lifetime to the given cache id
     *
     * @param string $id cache id
     * @param int $extraLifetime
     * @return boolean true if ok
     */
    public function touch($id, $extraLifetime)
    {
        $tmp = $this->_memcache->get($id, null, $token);
        if (is_array($tmp)) {
            $data = $tmp[0];
            $mtime = $tmp[1];
            if (!isset($tmp[2])) {
                // because this record is only with 1.7 release
                // if old cache records are still there...
                return false;
            }
            $lifetime = $tmp[2];
            $newLifetime = $lifetime - (time() - $mtime) + $extraLifetime;
            if ($newLifetime <=0) {
                return false;
            }

            $result = $this->_memcache->cas($token, $id, array($data, time(), $newLifetime), $newLifetime);

            return $result;
        }

        return false;
    }

    /**
     * Return an associative array of capabilities (booleans) of the backend
     *
     * The array must include these keys :
     * - automatic_cleaning (is automating cleaning necessary)
     * - tags (are tags supported)
     * - expired_read (is it possible to read expired cache records
     *                 (for doNotTestCacheValidity option for example))
     * - priority does the backend deal with priority when saving
     * - infinite_lifetime (is infinite lifetime can work with this backend)
     * - get_list (is it possible to get the list of cache ids and the complete list of tags)
     *
     * @return array associative of with capabilities
     */
    public function getCapabilities()
    {
        return array(
            'automatic_cleaning' => false,
            'tags' => false,
            'expired_read' => false,
            'priority' => false,
            'infinite_lifetime' => false,
            'get_list' => false
        );
    }
}
