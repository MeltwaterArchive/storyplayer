<?php

namespace DataSift\Stone\ExceptionsLib;

use PHPUnit_Framework_TestCase;

class Exxx_ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new Exxx_Exception(100, "public", "dev");

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof Exxx_Exception);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::__construct
     */
    public function testMustSetErrorCodeOnInstantiation()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $obj = new Exxx_Exception($expectedCode, "public", "dev");

        // ----------------------------------------------------------------
        // test the results

        $actualCode = $obj->getCode();
        $this->assertEquals($expectedCode, $actualCode);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::__construct
     */
    public function testMustSetPublicErrorMessageOnInstantiation()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = "this is the expected message";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new Exxx_Exception(503, $expectedMessage, "dev");

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $obj->getMessage();
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::__construct
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::getDevMessage
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::setDevMessage
     */
    public function testMustSetDevErrorMessageOnInstantiation()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = "this is the expected message";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new Exxx_Exception(503, "public", $expectedMessage);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $obj->getDevMessage();
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\Exxx_Exception::__toString
     */
    public function testCanConvertToString()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = 'public in ' . __FILE__ . ' at line ' . (__LINE__ + 1);
        $obj = new Exxx_Exception(503, "public", "dev");

        // ----------------------------------------------------------------
        // perform the change

        $actualMessage = (string)$obj;

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $actualMessage);
    }

}