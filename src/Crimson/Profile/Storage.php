<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Profile
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson\Profile;

interface Storage
{
    public function saveTimer($time);

    public function saveProfile($data);
}
