<?php

namespace DataSift\Stone\ExceptionsLib;

use PHPUnit_Framework_TestCase;

class E5xx_EngineErrorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError("", E_ERROR);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testErrorCodeIs500()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError("", E_ERROR);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedCode, $obj->getCode());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_Warning()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_WARNING: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_WARNING);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_Notice()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_NOTICE: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_NOTICE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_User_Error()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_USER_ERROR: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_USER_ERROR);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_User_Warning()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_USER_WARNING: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_USER_WARNING);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_User_Notice()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_USER_NOTICE: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_USER_NOTICE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testSupportsE_Recoverable_Error()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "E_RECOVERABLE_ERROR: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, E_RECOVERABLE_ERROR);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::__construct
     */
    public function testHandlesUnknownErrorCodes()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedMessage = "PHP engine error #125: {$errMsg}";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, 125);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $this->assertEquals($expectedMessage, $obj->getMessage());
    }

    /**
     * @covers DataSift\Stone\ExceptionsLib\E5xx_EngineError::getEngineError
     */
    public function testCanGetEngineErrorCode()
    {
        // ----------------------------------------------------------------
        // setup your test

        $errMsg = "My error message";
        $expectedCode = E_RECOVERABLE_ERROR;

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E5xx_EngineError($errMsg, $expectedCode);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E5xx_EngineError);
        $actualCode = $obj->getEngineError();
        $this->assertEquals($expectedCode, $actualCode);
    }

}