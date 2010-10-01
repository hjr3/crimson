<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Profile
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimsontest;

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'crimson/Profile.php';
require_once 'crimson/profile/Storage.php';

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    public function testTimer()
    {
        $p = new \crimson\Profile;
        $p->timerStart();
        foo();
        $t = $p->timerStop();

        $this->assertGreaterThan(0, $t);
    }

    public function testProfile()
    {
        $p = new \crimson\Profile;
        $p->profileStart();
        foo();
        $r = $p->profileStop();

        $this->assertTrue(is_array($r));
    }
} 

function bar($x) {
    if ($x > 0) {
        bar($x - 1);
    }
}

function foo() {
    for ($idx = 0; $idx < 2; $idx++) {
        bar($idx);
        $x = strlen("abc");
    }
}
