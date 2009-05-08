<?php
abstract class Crimson_Model_Abstract
{
    protected static $_db;

    protected static $_log;

    public function __construct()
    {
        $this->_log('Model ' . get_class($this) . ' created.', Zend_Log::INFO);
    }

    public function __destruct()
    {

    }

    final static public function setDb($db)
    {
        self::$_db = $db;
    }

    final static public function setLog($log)
    {
        self::$_log = $log;
    }

    final protected function _log($message, $level)
    {
        if (!self::$_log) {
            return false;
        }

        return self::$_log->log($message, $level);
    }

    final protected function _failure($error=null)
    {
        $result = new Crimson_Result();

        if ($error) {
            $result->addError($error);
        }

        return $result;
    }

    final protected function _success($data=null)
    {
        $result = new Crimson_Result();

        if ($data) {
            $result->addData($data);
        }

        return $result;
    }
}
