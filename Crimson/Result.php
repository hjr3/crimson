<?php
/**
 * Class to format responses based on ExtJs json format.
 *
 * The data and errors members are split out so the application can naively
 * add errors and messages to this object.  The existence of any errors will 
 * force the response to act like an error.
 * 
 * A response object containing either data or errors should be returned to 
 * the contrller from a model.
 * 
 * @package Crimson
 * @copyright 2009 Herman Radtke
 * @author Herman Radtke 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Crimson_Result
{
    /**
     * Data to send back to the view.
     * 
     * @var array
     * @access private
     */
    private $_data;

    /**
     * Errors to send back to the view.
     * 
     * @var array
     * @access private
     */
    private $_errors;

    /**
     * Initialize private error and data members to arrays.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_data = array();
        $this->_errors = array();
    }

    /**
     * Add key/value pairs of data.
     * 
     * @param array $data Data to be sent to the view.
     * @access public
     * @return void
     */
    public function addData($data)
    {
        if (is_array($data)) {
            $this->_data += $data;
        }
    }

    /**
     * Add key/value pairs of error messages.
     * 
     * @param array $errors Error messages to be sent to the view.
     * @access public
     * @return void
     */
    public function addError($errors)
    {
        if (is_array($errors)) {
            $this->_errors += $errors;
        }
    }

    /**
     * Format results into a json string that complies with ExtJs format.
     * 
     * @access public
     * @return string A json string of results.
     */
    public function __toString()
    {
        $err_flag = count($this->_errors);
        $response = array('success' => !$err_flag);

        if ($err_flag) {
            $response['errors'] = $this->_errors;
        } else {
                $response['data'] = $this->_data;
        }

        if (!function_exists('json_encode')) {
            throw new Exception('The json_encode function does not exist.');
        }

        return json_encode($response);
    }
}
