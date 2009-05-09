<?php
$path = dirname(dirname(__FILE__));
$path .= PATH_SEPARATOR . get_include_path();

set_include_path($path);

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true); 

class AllTests extends PHPUnit_Framework_TestSuite {
    public function __construct() 
    {
		$this->setName('Crimson Unit Tests');
		
		$this->addTestSuite('Crimson_ResultTest');
	
	}

    public static function suite() 
    {
		return new self();
	}
}

