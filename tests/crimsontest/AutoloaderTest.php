<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Autoloader
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimsontest;

require_once 'crimson/Autoloader.php';

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadsFromNamespace()
    {
       $class = 'crimsontest\\_files\\autoload\\test\\path\\Foo';
       \crimson\autoload($class);

       $this->assertTrue(class_exists($class, false));
    }

    public function testSplAutoloadRegister()
    {
       $class = '\\crimsontest\\_files\\autoload\\test\\path\\Foo';
        spl_autoload_register('\\crimson\\autoload');

       $obj = new $class;

       $this->assertTrue(($obj instanceof $class));
    }
} 
