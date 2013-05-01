<?php

namespace DataSift\Stone\ExceptionsLib;

use Exception;
use PHPUnit_Framework_TestCase;

class LegacyErrorCatcherTest extends PHPUnit_Framework_TestCase
{
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$obj = new LegacyErrorCatcher();

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof \DataSift\Stone\ExceptionsLib\LegacyErrorCatcher);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::callUserFuncArray
	 */
	public function testCanWrapCallables()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $expectedResult = 42;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = $wrapper->calluserFuncArray(function(){ return 42; });

	    // ----------------------------------------------------------------
	    // test the results
	    //
	    // explain what you expect to have happened

	    $this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::callUserFuncArray
	 */
	public function testCanPassParamsIntoWrappedCallables()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $expectedResult = 42;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = $wrapper->calluserFuncArray(function($result){ return $result; }, array($expectedResult));

	    // ----------------------------------------------------------------
	    // test the results
	    //
	    // explain what you expect to have happened

	    $this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::callUserFuncArray
	 */
	public function testCanWrapLegacyErrors()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();

	    // ----------------------------------------------------------------
	    // perform the change

	    $caughtException = false;
	    try{
	    	$wrapper->callUserFuncArray("does_not_exist");
	    } catch (E5xx_EngineError $e) {
	    	$caughtException = true;
	    }

	    // ----------------------------------------------------------------
	    // test the results
	    //
	    // explain what you expect to have happened

	    $this->assertTrue($caughtException);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::__handleLegacyError
	 */
	public function testThrowsNoExceptionForWarningsByDefault()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedErrors = array(
	    	E_NOTICE => false,
	    	E_USER_WARNING => false,
	    	E_USER_NOTICE => false,
			E_DEPRECATED => false,
			E_USER_DEPRECATED => false
		);

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualErrors = array();
		foreach ($expectedErrors as $code => $dummy) {
			$wrapper = new LegacyErrorCatcher();
			$actualErrors[$code] = $wrapper->__handleLegacyError($code, "", __FILE__);
		}

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedErrors, $actualErrors);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::__handleLegacyError
	 */
	public function testThrowsExceptionForErrorsByDefault()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedErrors = array(
	    	E_WARNING => true,
	    	E_USER_ERROR => true,
	    	E_RECOVERABLE_ERROR => true,
		);

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualErrors = array();
		foreach ($expectedErrors as $code => $dummy) {
			$wrapper = new LegacyErrorCatcher();
			$actualErrors[$code] = $wrapper->__handleLegacyError($code, "", __FILE__);
		}

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedErrors, $actualErrors);
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::__handleLegacyError
	 */
	public function testThrowsOneExceptionIfMultipleErrorsEncountered()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// we expect each of these error codes to be treated as an error
		// by the LegacyErrorCatcher
	    $expectedErrors = array(
	    	E_WARNING => true,
	    	E_USER_ERROR => true,
	    	E_RECOVERABLE_ERROR => true,
		);

		$expectedException = new E5xx_EngineError("", E_WARNING);

	    // unlike some of the other unit tests, we're going to reuse
	    // our wrapper more than once
		$wrapper = new LegacyErrorCatcher();

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualErrors = array();
		foreach ($expectedErrors as $code => $dummy) {
			$wrapper->__handleLegacyError($code, "", __FILE__);
			$actualErrors[$code] = $wrapper->hasPendingException();
		}

		// what is the pending exception?
		$actualException = $wrapper->getPendingException();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedErrors, $actualErrors);
	    $this->assertEquals($expectedException->getEngineError(), $actualException->getEngineError());
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::getWarningsAreFatal
	 */
	public function testCanGetWarningsAreFatal()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($wrapper->getWarningsAreFatal());
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::getWarningsAreFatal
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::setWarningsAreFatal
	 */
	public function testCanSetWarningsAreFatal()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $this->assertFalse($wrapper->getWarningsAreFatal());

	    // ----------------------------------------------------------------
	    // perform the change

	    $wrapper->setWarningsAreFatal();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($wrapper->getWarningsAreFatal());
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::hasPendingException
	 */
	public function testCanCheckForPendingException()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($wrapper->hasPendingException());
	}

	/**
	 * @covers DataSift\Stone\ExceptionsLib\LegacyErrorCatcher::getPendingException
	 */
	public function testCanGetPendingException()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $this->assertFalse($wrapper->hasPendingException());

	    // ----------------------------------------------------------------
	    // perform the change

	    $wrapper->__handleLegacyError(E_WARNING, "", __FILE__);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($wrapper->hasPendingException());

	    $actualException = $wrapper->getPendingException();
	    $this->assertTrue($actualException instanceof E5xx_EngineError);
	}

	public function testHasNoPendingExceptionAfterExceptionThrown()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $this->assertFalse($wrapper->hasPendingException());

	    // ----------------------------------------------------------------
	    // perform the change

	    $caughtException = false;
	    try{
	    	$wrapper->callUserFuncArray("does_not_exist");
	    } catch (E5xx_EngineError $e) {
	    	$caughtException = true;
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($caughtException);
	    $this->assertFalse($wrapper->hasPendingException());
	}

	public function testHasNoPendingExceptionAfterASuccessfulWrappedCall()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $wrapper = new LegacyErrorCatcher();
	    $this->assertFalse($wrapper->hasPendingException());

	    $expectedResult = 42;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = $wrapper->calluserFuncArray(function(){ return 42; });

	    // ----------------------------------------------------------------
	    // test the results
	    //
	    // explain what you expect to have happened

	    $this->assertEquals($expectedResult, $actualResult);
	    $this->assertFalse($wrapper->hasPendingException());
	}

}
