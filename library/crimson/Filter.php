<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Filter
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimson;

class Filter
{
    /**
     * Wrap filter_var_array with optional definition support.
     *
     * This method works like the standard 
     * {@link http://php.net/filter_var_array} function except it assumes
     * all parameters are required.  If a data parameter either fails the
     * filter or is not present, the entire data set is assumed to be
     * invalid.  If a parameter is flagged as optional, it will not cause
     * a failure if not present or the filter fails.
     * 
     * @param array $data An array of data to filter on.
     * @param array $definition Definition of filters and validators to apply.
     * @param array $optional Array of keys that are optional.
     * @return false|array Filtered array on success, otherwise false.
     */
    public function filter(array $data, array $definition, array $optional=array())
    {
        $input = filter_var_array($data, $definition);

        foreach ($input as $key => $value) {
            if (is_null($value) or ($value === false)) {
                if (!in_array($key, $optional)) {
                    return false;
                }
            }
        }

        return $input;
    }
}
