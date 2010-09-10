<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

$libDir =  __DIR__ . '/../library';
if (file_exists($libDir) && is_dir($libDir)) {
    set_include_path($libDir . PATH_SEPARATOR . get_include_path());
}

$testDir = __DIR__;
if (file_exists($testDir) && is_dir($testDir)) {
    set_include_path($testDir . PATH_SEPARATOR . get_include_path());
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

// set a default timezone only if one is not specified
// by the users php.ini settings
if (!ini_get('date.timezone')) {
    date_default_timezone_set('America/Los_Angeles');
}

// if the directory to the zend framework tests is specified, include it
// so the Memcache tests can be run
$zendTestDir = getenv('ZENDTEST');
if (file_exists($zendTestDir) && is_dir($zendTestDir)) {
    set_include_path($zendTestDir . PATH_SEPARATOR . get_include_path());

    define('ZENDTEST', true);
    define('TESTS_ZEND_CACHE_MEMCACHED_HOST', '127.0.0.1');
    define('TESTS_ZEND_CACHE_MEMCACHED_PORT', '11211');
}
