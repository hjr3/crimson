<?php
/**
 * Base class for creating models.
 * 
 * @abstract
 * @package Crimson
 * @copyright 2009 Herman Radtke
 * @author Herman Radtke 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
abstract class Crimson_Model_Abstract
{
    /**
     * Database object. 
     *
     * @access protected
     * @var object
     */
    protected static $_db;

    /**
     * Logging object. 
     *
     * @access protected
     * @var object
     */
    protected static $_log;

    /**
     * Log model creation.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_log('Model ' . get_class($this) . ' created.', Zend_Log::INFO);
    }

    /**
     * Store database object.
     *
     * The database object should be assigned to this abstract class once.  This
     * allows any model to make queries to the database without having to copy
     * the database object instantiated model.
     * 
     * @param object $db Database object.
     * @static
     * @final
     * @access public
     * @return void
     */
    final static public function setDb($db)
    {
        self::$_db = $db;
    }

    /**
     * Store logging object.
     *
     * The logging object should be assigned to this abstract class once.  This
     * allows any model to log messages without having to copy the logging
     * object for each instnatiated model.
     * 
     * @param object $log Logging object.
     * @static
     * @final
     * @access public
     * @return void
     */
    final static public function setLog($log)
    {
        self::$_log = $log;
    }

    /**
     * Standard logging method.
     * 
     * @param mixed $message Message to write to the log.
     * @param mixed $level Log level of the message.
     * @final
     * @access protected
     * @return void
     */
    final protected function _log($message, $level)
    {
        if (!self::$_log) {
            return;
        }

        self::$_log->log($message, $level);
    }

    /**
     * Convenience method to create a result with errors.
     * 
     * @param array $error Key/value pairs of error messages.
     * @final
     * @access protected
     * @return object A Crimson_Result object.
     */
    final protected function _failure($error)
    {
        if (!isset($error) || !is_array($error)) {
            throw new Exception('Invalid error parameter for Crimson_Result');
        }

        $result = new Crimson_Result();
        $result->addError($error);

        return $result;
    }

    /**
     * Convenience method to create a result with data.
     *
     * The caller only need to provide data if there is a message besides
     * "success" that needs to be delivered to the front-end.
     *
     * @param array $data Key/value pairs of data.
     * @final
     * @access protected
     * @return object A Crimson_Result object.
     */
    final protected function _success($data=array())
    {
        $result = new Crimson_Result();
        $result->addData($data);

        return $result;
    }
}
