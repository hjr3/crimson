<?php
/**
 * Class to format responses based on view requirements.
 *
 * The data and errors members are split out so the application can naively
 * add errors and messages to this object.  The existence of any values in 
 * errors will force the response to act like an error.
 * 
 * Creating a response object and passing it the view is the easiest way to 
 */
class Crimson_Result
{
    /**
     * Error flag
     * 
     * @var boolean
     */
    private $_isError;

    /**
     * Data to send back to the view
     * 
     * @var array
     */
    private $_data;

    /**
     * Errors to send back to the view
     * 
     * @var array
     */
    private $_errors;

    public function __construct()
    {
        $this->_isError = 0;
    }

    public function addData($data)
    {
        if (is_array($data)) {
            $this->_data += $data;
        }
    }

    public function addError($errors)
    {
        if (is_array($errors)) {
            $this->_errors += $errors;
        }
    }

    public function __toString()
    {	
        $response = array('success' => !$this->isError());

        if (isset($this->_errors)) {
            if (count($this->_errors)) {
                $response['errors'] = $this->_errors;
            }
        } else {
            if (count($this->_data)) {
                $response['data'] = $this->_data;
            }
        }

        return json_encode($response);
    }
}
