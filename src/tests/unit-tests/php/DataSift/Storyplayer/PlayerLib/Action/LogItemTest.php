<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    Libraries
 * @package     Storyplayer/PlayerLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;

class Action_LogItemTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$i   = new stdClass;
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
	    $obj = new Action_LogItem($i, 1);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Action_LogItem);
	    $this->assertEquals(1, $obj->getNestLevel());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::__construct
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsOpen
	 */
	public function testLogItemsAreNotOpenByDefault()
	{
	    // ----------------------------------------------------------------
    	// setup your test

		$i   = new stdClass;
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
	    $obj = new Action_LogItem($i, 1);

	    $this->assertTrue($obj instanceof Action_LogItem);
	    $this->assertEquals(1, $obj->getNestLevel());

	    // ----------------------------------------------------------------
	    // perform the change

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($obj->getIsOpen());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::__construct
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsComplete
	 */
	public function testLogItemsAreNotCompleteByDefault()
	{
	    // ----------------------------------------------------------------
    	// setup your test

		$i   = new stdClass;
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
	    $obj = new Action_LogItem($i, 1);

	    $this->assertTrue($obj instanceof Action_LogItem);
	    $this->assertEquals(1, $obj->getNestLevel());

	    // ----------------------------------------------------------------
	    // perform the change

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($obj->getIsComplete());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::startAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getStartTime
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::writeToLog
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsOpen
	 */
	public function testCanStartAnAction()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the message we are logging
    	$msg = "This is a test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($msg, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $this->assertNull($obj->getStartTime());
	    $this->assertFalse($obj->getIsOpen());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->startAction($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Action_LogItem);
	    $this->assertEquals(1, $obj->getNestLevel());
	    $this->assertNotNull($obj->getStartTime());
	    $this->assertTrue($obj->getIsOpen());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::endAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getEndTime
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getOpenAction
	 */
	public function testCanEndAnActionWithNoMessage()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the message we are logging
    	$startMsg = "This is a test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg);

	    // make sure the message started
	    $this->assertNotNull($obj->getStartTime());
	    $this->assertNull($obj->getEndTime());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endAction();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertNotNull($obj->getEndTime());
	    $this->assertNull($obj->getOpenAction());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::endAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getEndTime
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getOpenAction
	 */
	public function testCanEndAnActionWithMessage()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg = "This is a test message";
    	$endMsg   = "this is the end message";

    	// our DI container
		$i = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('... ' . $endMsg, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg);

	    // make sure the message started
	    $this->assertNotNull($obj->getStartTime());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endAction($endMsg);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertNotNull($obj->getEndTime());
	    $this->assertNull($obj->getOpenAction());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::newNestedAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getNestLevel
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getStartTime
	 */
	public function testCanStartANestedAction()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);

	    // make sure the message started
	    $this->assertNotNull($obj->getStartTime());

	    // ----------------------------------------------------------------
	    // perform the change

	    $nestedObj = $obj->newNestedAction();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($nestedObj instanceof Action_LogItem);
	    $this->assertEquals(2, $nestedObj->getNestLevel());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::captureOutput
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsOpen
	 */
	public function testCanCaptureOutput()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";
    	$startMsg2 = "This is a nested message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('  ' . $startMsg2, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);

	    // make sure the message started
	    $this->assertNotNull($obj->getIsOpen());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->captureOutput($startMsg2);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::startAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::newNestedAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getNestLevel
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getStartTime
	 */
	public function testCanStartANestedActionInsideANestedAction()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";
    	$startMsg2 = "This is the nested test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('  ' . $startMsg2, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);

	    // make sure the message started
	    $this->assertNotNull($obj->getStartTime());

	    // ----------------------------------------------------------------
	    // perform the change

	    $nestedObj1 = $obj->newNestedAction();
	    $nestedObj1->startAction($startMsg2);
	    $nestedObj2 = $obj->newNestedAction();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($nestedObj1 instanceof Action_LogItem);
	    $this->assertEquals(2, $nestedObj1->getNestLevel());

	    $this->assertTrue($nestedObj2 instanceof Action_LogItem);
	    $this->assertEquals(3, $nestedObj2->getNestLevel());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::endAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::closeAllOpenSubActions
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getStartTime
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsComplete
	 */
	public function testEndingAnActionEndsAllNestedActionsToo()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";
    	$startMsg2 = "This is the nested test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('  ' . $startMsg2, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);

	    // make sure the message started
	    $this->assertNotNull($obj->getStartTime());

	    // start our nested actions
	    $nestedObj1 = $obj->newNestedAction();
	    $nestedObj1->startAction($startMsg2);
	    $nestedObj2 = $obj->newNestedAction();
	    $this->assertTrue($nestedObj1 instanceof Action_LogItem);
	    $this->assertEquals(2, $nestedObj1->getNestLevel());
	    $this->assertTrue($nestedObj2 instanceof Action_LogItem);
	    $this->assertEquals(3, $nestedObj2->getNestLevel());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endAction();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($nestedObj2->getIsComplete());
	    $this->assertTrue($nestedObj1->getIsComplete());
	    $this->assertTrue($obj->getIsComplete());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::startAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getIsOpen
	 */
	public function testCanSeeIfAStartedActionIsOpen()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the message we are logging
    	$msg = "This is a test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($msg, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $this->assertNull($obj->getStartTime());

	    // ----------------------------------------------------------------
	    // perform the change

	    // let's get this action started
	    $obj->startAction($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Action_LogItem);
	    $this->assertEquals(1, $obj->getNestLevel());
	    $this->assertTrue($obj->getIsOpen());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::addStep
	 */
	public function testAddAStep()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";
    	$startMsg2 = "This is the nested test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('  ' . $startMsg2, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);
	    $this->assertSame($obj, $obj->getOpenAction());

	    // make sure the message started
	    $this->assertTrue($obj->getIsOpen());

	    // what is our sub-step?
	    $func = function() { return 1; };

	    // ----------------------------------------------------------------
	    // perform the change

	    $return  = $obj->addStep($startMsg2, $func);

	    // ----------------------------------------------------------------
	    // test the results

	    // we should have a return value from our callback
	    $this->assertEquals(1, $return);

	    // our unit under test should be the open action
	    // (i.e. there shouldn't be an open sub-action)
	    $this->assertSame($obj, $obj->getOpenAction());

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::startStep
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::endStep
	 * @covers DataSift\Storyplayer\PlayerLib\Action_LogItem::getOpenAction
	 */
	public function testAddAStepWithoutUsingACallable()
	{
	    // ----------------------------------------------------------------
    	// setup your test

    	// the messages we are logging
    	$startMsg1 = "This is a test message";
    	$startMsg2 = "This is the nested test message";

    	// our DI container
		$i   = new stdClass;

		// our mocked output object
		$i->output = Mockery::mock("DataSift\Storyplayer\Output");
		$i->output->shouldReceive('logPhaseActivity')->once()->with($startMsg1, null);
		$i->output->shouldReceive('logPhaseActivity')->once()->with('  ' . $startMsg2, null);

		// our unit under test
	    $obj = new Action_LogItem($i, 1);
	    $obj->startAction($startMsg1);

	    // make sure the message started
	    $this->assertTrue($obj->getIsOpen());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->startStep($startMsg2);
	    $nestedObj = $obj->getOpenAction();
	    $this->assertNotSame($obj, $nestedObj);
	    $this->assertTrue($nestedObj->getIsOpen());

	    $obj->endStep();
	    $this->assertTrue($nestedObj->getIsComplete());

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}


}
