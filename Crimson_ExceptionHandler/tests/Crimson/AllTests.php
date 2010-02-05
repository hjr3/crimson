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

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Crimson_AllTests::main');
}

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'Crimson/ExceptionHandlerTest.php';

class Crimson_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite(), array());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Crimson - All Tests');

        $suite->addTestSuite('Crimson_ExceptionHandlerTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Crimson_AllTests::main') {
    AllTests::main();
}
