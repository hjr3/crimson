<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Filter
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimsontest;

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'crimson/Filter.php';

class FilterTest extends \PHPUnit_Framework_TestCase
{
    protected $Filter;

    protected function setUp()
    {
        $this->Filter = new \crimson\Filter;
    }

    public function testFilterReturnsInput()
    {
        $data = array(
            'foo' => 'bar',
        );

        $def = array(
            'foo' => FILTER_SANITIZE_STRING,
        );

        $input = $this->Filter->filter($data, $def);

        $expected = array(
            'foo' => 'bar',
        );

        $this->assertEquals($expected, $input);
    }

    public function testFilterReturnsFalseOnInvalid()
    {
        $data = array(
            'foo' => 'bar',
        );

        $def = array(
            'foo' => FILTER_VALIDATE_INT,
        );

        $input = $this->Filter->filter($data, $def);

        $this->assertFalse($input);
    }

    public function testFilterReturnInputOnOptional()
    {
        $data = array(
            'foo' => 'bar',
        );

        $def = array(
            'foo' => FILTER_VALIDATE_INT,
        );

        $optional = array('foo');

        $input = $this->Filter->filter($data, $def, $optional);

        $expected = array(
            'foo' => null,
        );

        $this->assertEquals($expected, $input);
    }

    public function testFilterComplex()
    {
        $data = array(
            'foo' => 'bar',
            'baz' => 1,
            'email' => 'test@example.com',
        );

        $def = array(
            'foo' => FILTER_SANITIZE_STRING,
            'baz' => FILTER_VALIDATE_INT,
            'email' => FILTER_VALIDATE_EMAIL,
        );

        $input = $this->Filter->filter($data, $def);

        $expected = array(
            'foo' => 'bar',
            'baz' => 1,
            'email' => 'test@example.com',
        );

        $this->assertEquals($expected, $input);
    }

    public function testFilterComplexOneOptional()
    {
        $data = array(
            'foo' => 'bar',
            'baz' => '1',
            'email' => 'test@example.',
        );

        $def = array(
            'foo' => FILTER_SANITIZE_STRING,
            'baz' => FILTER_VALIDATE_INT,
            'email' => FILTER_VALIDATE_EMAIL,
        );

        $optional = array('email');

        $input = $this->Filter->filter($data, $def, $optional);

        $expected = array(
            'foo' => 'bar',
            'baz' => 1,
            'email' => null,
        );

        $this->assertEquals($expected, $input);
    }

    public function testFilterComplexOneOptionalOneFailure()
    {
        $data = array(
            'foo' => 'bar',
            'baz' => '1f',
            'email' => 'test@example.',
        );

        $def = array(
            'foo' => FILTER_SANITIZE_STRING,
            'baz' => FILTER_VALIDATE_INT,
            'email' => FILTER_VALIDATE_EMAIL,
        );

        $optional = array('baz');

        $input = $this->Filter->filter($data, $def, $optional);

        $this->assertFalse($input);
    }
} 
