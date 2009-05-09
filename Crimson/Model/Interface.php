<?php
/**
 * Interface for base model class.
 * 
 * @package 
 * @copyright 2009 Herman Radtke
 * @author Herman Radtke 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
interface Crimson_Model_Interface
{
    /**
     * Depedency injection method.
     *
     * Model dependencies are usually parameters of the constructor.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function build();
}
