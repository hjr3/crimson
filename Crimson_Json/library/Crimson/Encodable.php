<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Json
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
interface Crimson_Encodable
{
    public function encode();
    public function decode($value);
}
