<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Json
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson\Test;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testEncodeScalars()
    {
        $scalars = array(
            array(105, 105),
            array(3.14, 3.14),
            array('wibble', '"wibble"'),
            array(false, 'false'),
        );

        foreach($scalars as $scalar) {
            list($given, $expected) = $scalar;
            $this->assertEquals($expected, \Crimson\Json::encode($given));
        }
    }

    public function testEncodeArray()
    {
        $given = array(1,2,3,4,5);

        $expected = '[1,2,3,4,5]';

        $this->assertEquals($expected, \Crimson\Json::encode($given));

        $given = array(
            'a' => 1,
            5,
            false,
            'trouble at mill',
            'foo' => 'bar',
        );

        $expected = '{"a":1,"0":5,"1":false,"2":"trouble at mill","foo":"bar"}';

        $this->assertEquals($expected, \Crimson\Json::encode($given));
    }

    public function testEncodeStdClass()
    {
        $given = new \stdClass;
        $given->p = 'hp';
        $given->x = 1;
        $given->status = false;

        $expected = '{"p":"hp","x":1,"status":false}';

        $this->assertEquals($expected, \Crimson\Json::encode($given));
    }

    public function testEncodeEncodable()
    {
        $expected = '{"inside":"encode"}';
        $c = $this->getMock('\Crimson\Json\Encodable');

        $c->expects($this->once())
            ->method('encode')
            ->will($this->returnValue($expected));

        $this->assertEquals($expected, \Crimson\Json::encode($c));
    }

    public function testDecodeScalars()
    {
        $scalars = array(
            array(105, 105),
            array(3.14, 3.14),
            array('"wibble"', 'wibble'),
            array('false', false),
        );

        foreach($scalars as $scalar) {
            list($given, $expected) = $scalar;
            $this->assertEquals($expected, \Crimson\Json::decode($given));
        }
    }

    public function testDecodeArray()
    {
        $given = '[1,2,3,4,5]';

        $expected = array(1,2,3,4,5);

        $this->assertEquals($expected, \Crimson\Json::decode($given));

        $given = '{"a":1,"0":5,"1":false,"2":"trouble at mill","foo":"bar"}';

        $result = \Crimson\Json::decode($given);

        $this->assertAttributeEquals(1, 'a', $result);
        $this->assertAttributeEquals(5, '0', $result);
        $this->assertAttributeEquals(false, '1', $result);
        $this->assertAttributeEquals('trouble at mill', '2', $result);
        $this->assertAttributeEquals('bar', 'foo', $result);
    }

    public function testDecodeStdClass()
    {
        $given = '{"p":"hp","x":1,"status":false}';

        $result = \Crimson\Json::decode($given);

        $this->assertAttributeEquals('hp', 'p', $result);
        $this->assertAttributeEquals(1, 'x', $result);
        $this->assertAttributeEquals(false, 'status', $result);
    }

    /**
     * This test is ensuring that the decode method is called if a class
     * implements Encodable.  The decode method is normally not expected to 
     * return anything.
     */
    public function testDecodeEncodable()
    {
        $expected = true;
        $c = $this->getMock('\Crimson\Json\Encodable');

        $c->expects($this->once())
            ->method('decode')
            ->will($this->returnValue($expected));

        $this->assertEquals($expected, \Crimson\Json::decode($c));
    }
} 
