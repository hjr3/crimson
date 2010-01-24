<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_Nemcache
 * @subpackage Test
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: MemcachedBackendTest.php 17363 2009-08-03 07:40:18Z bkarwin $
 */
 
/**
 * Zend_Cache
 */
require_once 'Zend/Cache.php';
require_once 'Crimson/Memcache.php';

/**
 * Common tests for backends
 */
require_once 'Zend/Cache/CommonExtendedBackendTest.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Cache
 */
class Crimson_MemcachedTest extends Zend_Cache_CommonExtendedBackendTest {
    
    protected $_instance;
 
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct('Crimson_Memcache', $data, $dataName);
    }
       
    public function setUp($notag = true)
    {        
        $server = array(
            'host' => TESTS_ZEND_CACHE_MEMCACHED_HOST,
            'port' => TESTS_ZEND_CACHE_MEMCACHED_PORT
        );
        $options = array(
            'servers' => array(0 => $server),
            'persistent' =>  'zend-test-persistent'
        );
        $this->_instance = new Crimson_Memcache($options);
        parent::setUp($notag);    
    }
    
    public function tearDown()
    {
        parent::tearDown();
        unset($this->_instance);
        // We have to wait after a memcache flush
        sleep(1);
    }
    
    public function testConstructorCorrectCall()
    {
        $test = new Crimson_Memcache(); 
    }
    
    public function testCleanModeOld() 
    {
        $this->_instance->setDirectives(array('logging' => false));
        $this->_instance->clean('old');
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testCleanModeMatchingTags() 
    {
        $this->_instance->setDirectives(array('logging' => false));
        $this->_instance->clean('matchingTag', array('tag1'));
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testCleanModeNotMatchingTags() 
    {
        $this->_instance->setDirectives(array('logging' => false));        
        $this->_instance->clean('notMatchingTag', array('tag1'));
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testGetWithCompression() 
    {
        $this->_instance->setOption('compression', true);
        $this->testGetWithAnExistingCacheIdAndUTFCharacters();
    }
    
    public function testConstructorWithAnAlternativeSyntax()
    {
        $server = array(
            'host' => TESTS_ZEND_CACHE_MEMCACHED_HOST,
            'port' => TESTS_ZEND_CACHE_MEMCACHED_PORT,
            'persistent' => TESTS_ZEND_CACHE_MEMCACHED_PERSISTENT
        );
        $options = array(
            'servers' => $server
        );
        $this->_instance = new Crimson_Memcache($options);
        $this->testGetWithAnExistingCacheIdAndUTFCharacters();
    }
    
    // Because of limitations of this backend...
    public function testGetWithAnExpiredCacheId() {}
    public function testCleanModeMatchingTags2() {}
    public function testCleanModeNotMatchingTags2() {}
    public function testCleanModeNotMatchingTags3() {}
    public function testSaveCorrectCall()
    {
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveCorrectCall();
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testSaveWithNullLifeTime()
    {
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveWithNullLifeTime();
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testSaveWithSpecificLifeTime() 
    {
        
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveWithSpecificLifeTime();
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testGetMetadatas($notag = false)
    {
        parent::testGetMetadatas(true);
    }

    public function testGetWithANonExistingCacheId()
    {
        $this->assertNull($this->_instance->load('barbar'));
    }

}


