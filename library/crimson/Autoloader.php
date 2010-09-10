<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimson;

/**
 * Very simple namespace autoloader
 *
 * This function expects to be called from the autoloader with no leading '\'.
 * 
 * @param string $class namespaced class name
 */
function autoload($class)
{
    if (class_exists($class, false) || interface_exists($class, false)) {
        return;
    }

    // don't try to autoload if no namespace characters are found
    $p = strrpos($class, '\\');
    if (!$p) {
        return;
    }

    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    include $file;

    if (!class_exists($class, false) && !interface_exists($class, false)) {
        throw new RuntimeException("Class \"{$class}\" was not found");
    }
}
