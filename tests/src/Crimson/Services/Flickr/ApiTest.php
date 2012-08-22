<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Test
 * @subpackage Flickr API test
 * @copyright 2011 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson\Test;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected $api_key = 'foo';

    protected $test_response = '{"method":{"_content":"flickr.test.echo"}, "format":{"_content":"json"}, "foo":{"_content":"bar"}, "api_key":{"_content":"fac6f6719d42cac485e461bedc913fd8"}, "stat":"ok"}';

    public function testRequestBadReturnsFalse()
    {
        $flickr = $this->getMock('\Crimson\services\flickr\Api', array('send'), array($this->api_key));

        $response = $flickr->request('method', array());

        $this->assertFalse($response);

        $flickr
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->test_response));

        $response = $flickr->request('method', array());

        $this->assertEquals(json_decode($this->test_response, true), $response);
    }

    public function testGetLastUrl()
    {
        $flickr = $this->getMock('\Crimson\services\flickr\Api', array('send'), array($this->api_key));

        $this->assertEquals('', $flickr->getLastUrl());

        $flickr->request('method', array());
        $this->assertEquals('http://api.flickr.com/services/rest/?method=method&api_key=foo&format=json&nojsoncallback=1', $flickr->getLastUrl());
    }
} 
