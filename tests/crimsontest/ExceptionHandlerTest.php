<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Nemcache
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */


namespace crimsontest;

require_once __DIR__ . '/../TestHelper.php';

require_once 'crimson/ExceptionHandler.php';

function dummy_handler($e) {}

class ExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $ExceptionHandler;

    public function setUp()
    {
        $this->ExceptionHandler = new \crimson\ExceptionHandler;
    }

    public function tearDown()
    {
        restore_exception_handler();
    }

    public function testConstructor()
    {
        // best way i could see to determine the current exception handler
        // was to set a fake exception handler and use the return value
        $handler = set_exception_handler('\crimsontest\dummy_handler');

        $this->assertTrue($handler[0] instanceof \crimson\ExceptionHandler);
        $this->assertEquals('handle', $handler[1]);
    }

    public function testHandle()
    {
        $logFile = __DIR__ . '/_files/test.log';
        if (file_exists($logFile)) {
            if (!unlink($logFile)) {
                $this->fail('Unable to remove logfile');
            }
        }

        ini_set('error_log', $logFile);

        try {
            throw new \Exception('i hope i get handled');
        } catch (\Exception $e) {
            $this->ExceptionHandler->handle($e);
        }

        $this->assertFileExists($logFile);

        $log = file_get_contents($logFile);
        $this->assertGreaterThan(0, count($log));
    }
} 
