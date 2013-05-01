<?php

namespace DataSift\Stone\ExceptionsLib;

use PHPUnit_Framework_TestCase;

class E5xx_NotImplementedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_NotImplemented::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_NotImplemented(__METHOD__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_NotImplemented);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_NotImplemented::__construct
     */
    public function testErrorCodeIs500()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_NotImplemented(__METHOD__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_NotImplemented);
        $this->assertEquals($expectedCode, $obj->getCode());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_NotImplemented::__construct
     */
    public function testErrorMessageIsTheMethodNotImplemented()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = "Not implemented: " . __METHOD__;

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_NotImplemented(__METHOD__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_NotImplemented);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

}