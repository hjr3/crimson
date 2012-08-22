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

namespace Crimson\Test;

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    public function testTimer()
    {
        $p = new \Crimson\Profile;
        $p->timerStart();
        foo();
        $t = $p->timerStop();

        $this->assertGreaterThan(0, $t);
    }

    /**
     * @skip
     */
    public function testProfile()
    {
        if (!function_exists('xhprof_enable')) {
            $this->markTestSkipped('The xhprof functions are not available');
        }
        $p = new \Crimson\Profile;
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
