<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Url
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'Crimson/Url.php';

class Crimson_UrlTest extends PHPUnit_Framework_TestCase
{
    public function testUrl()
    {
        $baseUrl = 'http://www.example.com';
        $path = '/some/web/path';

        $expected = "{$baseUrl}{$path}";

        $url = Crimson_Url::absolute($baseUrl);
        $this->assertEquals($expected, $url($path));
    }

    public function testUrlMultipleUse()
    {
        $baseUrl = 'http://www.example.com';
        $path = '/some/web/path';

        $expected = "{$baseUrl}{$path}";

        $url = Crimson_Url::absolute($baseUrl);
        $this->assertEquals($expected, $url($path));

        $path = '/another/web/path';

        $expected = "{$baseUrl}{$path}";

        $this->assertEquals($expected, $url($path));
    }

    public function testMultipleUrlFuncs()
    {
        $baseUrl = 'http://www.example.com';
        $path = '/some/web/path';

        $expected1 = "{$baseUrl}{$path}";

        $url1 = Crimson_Url::absolute($baseUrl);
        $this->assertEquals($expected1, $url1($path));

        $baseUrl = 'http://foo.example.com';
        $path = '/another/web/path';

        $expected2 = "{$baseUrl}{$path}";

        $url2 = Crimson_Url::absolute($baseUrl);
        $this->assertEquals($expected2, $url2($path));
    }

    public function testRotateUrl()
    {
        $sub = 'www';
        $tld = 'example.com';
        $rotations = 2;
        $path = '/some/web/path';

        $expected1 = "http://{$sub}1.{$tld}{$path}";
        $expected2 = "http://{$sub}2.{$tld}{$path}";

        $url = Crimson_Url::rotate($sub, $tld, $rotations);

        $this->assertEquals($expected1, $url($path));
        $this->assertEquals($expected2, $url($path));
        $this->assertEquals($expected1, $url($path));
    }

    public function testRotateUrlWithProtocol()
    {
        $sub = 'www';
        $tld = 'example.com';
        $rotations = 2;
        $protocol = 'https';
        $path = '/some/web/path';

        $expected = "{$protocol}://{$sub}1.{$tld}{$path}";

        $url = Crimson_Url::rotate($sub, $tld, $rotations, $protocol);

        $this->assertEquals($expected, $url($path));
    }
} 
