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
class Crimson_Json
{
    /**
     * Serialize a value into JSON.
     *
     * This method honors the Encoable interface.  If an object implements this
     * interface, the object's encode method will be used to serialize the 
     * object.
     * 
     * @param Encodable|mixed $value The value to serialize into JSON.
     * @return mixed|false The serialized JSON object or false on error.
     */
    public static function encode($value)
    {
        if ($value instanceof Crimson_Encodable) {
            return $value->encode();
        }

        return json_encode($value);
    }

    /**
     * Decode a JSON string into the appropriate php type.
     * 
     * @param mixed $value The value to decode.
     * @return mixed|null The php value or null if value cannot be decoded.
     */
    public static function decode($value)
    {
        if ($value instanceof Crimson_Encodable) {
            return $value->decode($value);
        }

        return json_decode($value);
    }
}
