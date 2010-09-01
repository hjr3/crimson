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

require_once 'PHPUnit/Framework/TestCase.php';
