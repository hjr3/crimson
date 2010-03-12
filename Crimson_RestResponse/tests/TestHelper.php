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

/**
 * Tests are dependent upon Zend Framework test infrastructure
 */
$zendTestDir =  '/home/hradtke/sandbox/ZendFramework-1.9.5/tests';

if (file_exists($zendTestDir) && is_dir($zendTestDir)) {
    set_include_path($zendTestDir . PATH_SEPARATOR . get_include_path());
}

require_once $zendTestDir . '/TestHelper.php';

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$libDir = dirname(__FILE__) . '/../library';
if (file_exists($libDir) && is_dir($libDir)) {
    set_include_path($libDir . PATH_SEPARATOR . get_include_path());
}
