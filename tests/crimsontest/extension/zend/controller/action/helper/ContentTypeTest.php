<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_RestResponse
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace crimsontest\extension\zend\controller\action\helper;

require_once dirname(__FILE__) . '/../../../../../../TestHelper.php';
require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Zend/Controller/Action/HelperBroker.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';
require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Request/HttpTestCase.php';
require_once 'Zend/Controller/Response/Http.php';
require_once 'Zend/Layout.php';

require_once 'crimson/extension/zend/controller/action/helper/ContentType.php';

class ContentTypeTest extends \PHPUnit_Framework_TestCase 
{
    public function setUp()
    {
        Zend_Controller_Action_Helper_RestResponseTest_Layout::resetMvcInstance();

        $this->request = new \Zend_Controller_Request_HttpTestCase;

        $this->response = new \Zend_Controller_Response_Http;
        $this->response->headersSentThrowsException = false;

        $front = \Zend_Controller_Front::getInstance();
        $front->resetInstance();
        $front->setResponse($this->response);
        $front->setRequest($this->request);

        $this->viewRenderer = new \Zend_Controller_Action_Helper_ViewRenderer();
        \Zend_Controller_Action_HelperBroker::addHelper($this->viewRenderer);
        
        $this->helper = new \crimson\extension\zend\controller\action\helper\ContentType;
    }

    public function verifyHeader($expected)
    {
        $headers = $this->response->getHeaders();

        $found = false;
        foreach ($headers as $header) {
            if ('Content-Type' == $header['name']) {
                $found = true;
                $value = $header['value'];
                break;
            }
        }
        $this->assertTrue($found);
        $this->assertEquals($expected, $value);
    }

    public function testRestResponseHelperSetsResponseBody()
    {
        $this->request->setHeader('Accept', 'text/html');

        $this->helper->response('test');

        $body = $this->response->getBody();

        $this->assertEquals('test', $body);
    }

    public function testRestResponseHelperSetsResponseHeader()
    {
        $this->request->setHeader('Accept', 'text/html');

        $this->helper->response('test');

        $this->verifyHeader('text/html');
    }

    public function testRestResponseHelperWildcardAccept()
    {
        $this->request->setHeader('Accept', '*/*');

        $this->helper->response('test');

        $this->verifyHeader('text/html');

        $this->assertEquals('test', $this->response->getBody());
    }

    public function testRestResponseHelperDirect()
    {
        $this->request->setHeader('Accept', 'text/html');

        $this->helper->direct('test');

        $this->assertEquals('test', $this->response->getBody());
    }

    public function testRestResponseHelperAcceptJson()
    {
        $header = 'application/json';

        $givenBody = array(1,2,3,4);
        $expectedBody = '[1,2,3,4]';

        $this->request->setHeader('Accept', $header);

        $this->helper->response($givenBody);

        $this->verifyHeader($header);

        $this->assertEquals($expectedBody, $this->response->getBody());
    }
}

/**
 * Zend_Layout subclass to allow resetting MVC instance
 */
class Zend_Controller_Action_Helper_RestResponseTest_Layout extends \Zend_Layout
{
    public static function resetMvcInstance()
    {
        self::$_mvcInstance = null;
    }
}
