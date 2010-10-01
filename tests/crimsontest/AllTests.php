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

namespace crimsontest;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', '\crimsontest\AllTests::main');
}

require_once __DIR__ . '/../TestHelper.php';
require_once __DIR__ . '/AutoloaderTest.php';
require_once __DIR__ . '/ExceptionHandlerTest.php';
require_once __DIR__ . '/JsonTest.php';
require_once __DIR__ . '/UrlTest.php';
require_once __DIR__ . '/ProfileTest.php';
require_once __DIR__ . '/extension/zend/controller/action/helper/ContentTypeTest.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite(), array());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Crimson - All Tests');

        $suite->addTestSuite('\crimsontest\AutoloaderTest');
        $suite->addTestSuite('\crimsontest\ExceptionHandlerTest');
        $suite->addTestSuite('\crimsontest\JsonTest');
        $suite->addTestSuite('\crimsontest\UrlTest');
        $suite->addTestSuite('\crimsontest\ProfileTest');
        $suite->addTestSuite('\crimsontest\extension\zend\controller\action\helper\ContentTypeTest');

        if (defined('ZENDTEST')) {
            require_once __DIR__ . '/extension/zend/cache/backend/MemcacheTest.php';
            $suite->addTestSuite('\crimsontest\extension\zend\cache\backend\MemcacheTest');
        }

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == '\crimsontest\AllTests::main') {
    AllTests::main();
}
