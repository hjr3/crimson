<?php
class Crimson_ResultTest extends PHPUnit_Framework_TestCase 
{
    private $Crimson_Result;
	
    protected function setUp() 
    {
        $this->Crimson_Result = new Crimson_Result;
		parent::setUp ();
	}
	
    protected function tearDown() 
    {
		parent::tearDown ();
	}
	
    public function __construct() 
    {
	}

    public function testResultConstruct()
    {
        $expected = array();

        $dataPrivate = $this->readAttribute($this->Crimson_Result, '_data');
        $this->assertSame($expected, $dataPrivate);

        $errorsPrivate = $this->readAttribute($this->Crimson_Result, '_errors');
        $this->assertSame($expected, $errorsPrivate);
    }

    public function testAddData()
    {
        $data = array('test' => 'data');
        $this->Crimson_Result->addData($data);

        $dataPrivate = $this->readAttribute($this->Crimson_Result, '_data');
        $this->assertArrayHasKey(key($data), $dataPrivate);
        $this->assertEquals(current($data), $dataPrivate[key($data)]);
    }

    public function testAddData2()
    {
        $data = array('test' => 'data');
        $this->Crimson_Result->addData($data);

        $data2 = array('test2' => 'data2');
        $this->Crimson_Result->addData($data2);

        $dataPrivate = $this->readAttribute($this->Crimson_Result, '_data');

        $this->assertArrayHasKey(key($data2), $dataPrivate);
        $this->assertEquals(current($data2), $dataPrivate[key($data2)]);
    }

    public function testAddError()
    {
        $error = array('test' => 'error');
        $this->Crimson_Result->addError($error);

        $errorPrivate = $this->readAttribute($this->Crimson_Result, '_errors');
        $this->assertArrayHasKey(key($error), $errorPrivate);
        $this->assertEquals(current($error), $errorPrivate[key($error)]);
    }

    public function testAddError2()
    {
        $error = array('test' => 'error');
        $this->Crimson_Result->addError($error);

        $error2 = array('test2' => 'error2');
        $this->Crimson_Result->addError($error2);

        $errorPrivate = $this->readAttribute($this->Crimson_Result, '_errors');

        $this->assertArrayHasKey(key($error2), $errorPrivate);
        $this->assertEquals(current($error2), $errorPrivate[key($error2)]);
    }

    public function testToStringData()
    {
        $expected = '{"success":true,"data":{"test":"data"}}';

        $data = array('test' => 'data');
        $this->Crimson_Result->addData($data);

        $actual = $this->Crimson_Result->__toString();
        $this->assertEquals($expected, $actual);
    }

    public function testToStringErrors()
    {
        $expected = '{"success":false,"errors":{"test":"error"}}';

        $data = array('test' => 'error');
        $this->Crimson_Result->addError($data);

        $actual = $this->Crimson_Result->__toString();
        $this->assertEquals($expected, $actual);
    }
}
