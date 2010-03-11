<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_RestResponse
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class Crimson_Controller_Action_Helper_RestResponse extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Strategy pattern: call helper as helper broker method
     * 
     * @param mixed $content 
     */
    public function direct($content)
    {
        $this->response($data);
    }

    /**
     * Format response based on HTTP Accept header media type.
     *
     * This method is naive in that it expects explicit content types to
     * determine the repsonse.  Use of wildcards will match the default case.
     *
     * @param mixed $content The content of the response.
     */
    public function response($content)
    {
        $request = $this->getRequest();
        $accept = $request->getHeader('Accept');

        $response = $this->getResponse();
        switch (true) {
            case (strstr($accept, 'application/json')):
                $response->setHeader('Content-type', 'application/json')
                    ->setBody(json_encode($content));
                break;
            case (strstr($accept, 'text/html')):
            default:
                $response->setHeader('Content-type', 'text/html')
                    ->setBody($content);
                break;
        }
    }
}
