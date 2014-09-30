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

class Action_LoggerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$i   = new stdClass;
	    $obj = new Action_Logger($i);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Action_Logger);
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::startAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::getOpenAction
	 */
	public function testCanStartAnAction()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // the message we want to log
	    $expectedMsg = "This is a test message";

	    // our mock output facade
		$output = Mockery::mock("DataSift\Storyplayer\Output");
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg, null);

	    // our mock DI container
	    $i = new stdClass();
	    $i->output = $output;

	    // and, our test subject
	    $obj = new Action_Logger($i);

	    // ----------------------------------------------------------------
	    // perform the change

	    $log = $obj->startAction($expectedMsg);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($log instanceof Action_LogItem);
	    $action = $obj->getOpenAction();
	    $this->assertSame($action, $log);

	    // all done
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::startAction
	 */
	public function testCanStartANestedAction()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // the message we want to log
	    $msg = "This is a test message";
	    $expectedMsg1 = $msg;
	    $expectedMsg2 = "  " . $msg;

	    // our mock output facade
		$output = Mockery::mock("DataSift\Storyplayer\Output");
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg1, null);
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg2, null);

	    // our mock DI container
	    $i = new stdClass();
	    $i->output = $output;

	    // and, our test subject
	    $obj = new Action_Logger($i);

	    // ----------------------------------------------------------------
	    // perform the change

	    $log1 = $obj->startAction($msg);
	    $log2 = $obj->startAction($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($log1 instanceof Action_LogItem);
	    $action = $obj->getOpenAction();
	    $this->assertSame($action, $log1);

	    $subAction = $action->getOpenAction();
	    $this->assertSame($subAction, $log2);

	    // all done
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::closeAllOpenActions
	 */
	public function testCanCloseAnOpenAction()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // the message we want to log
	    $msg = "This is a test message";

	    // our mock output facade
		$output = Mockery::mock("DataSift\Storyplayer\Output");
		$output->shouldReceive('logPhaseActivity')->once()->with($msg, null);

	    // our mock DI container
	    $i = new stdClass();
	    $i->output = $output;

	    // and, our test subject
	    $obj = new Action_Logger($i);
	    $log = $obj->startAction($msg);

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->closeAllOpenActions();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($log->getIsComplete());
	    $action = $obj->getOpenAction();
	    $this->assertNull($action);

	    // all done
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::closeAllOpenActions
	 */
	public function testCanCallCloseAllOpenActionsWheNoActionsOpen()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // our mock DI container
	    $i = new stdClass();

	    // and, our test subject
	    $obj = new Action_Logger($i);

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->closeAllOpenActions();

	    // ----------------------------------------------------------------
	    // test the results

	    $action = $obj->getOpenAction();
	    $this->assertNull($action);

	    // all done
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::closeAllOpenActions
	 */
	public function testCanCloseNestedOpenActions()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // the message we want to log
	    $msg = "This is a test message";
	    $expectedMsg1 = $msg;
	    $expectedMsg2 = "  " . $msg;

	    // our mock output facade
		$output = Mockery::mock("DataSift\Storyplayer\Output");
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg1, null);
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg2, null);

	    // our mock DI container
	    $i = new stdClass();
	    $i->output = $output;

	    // and, our test subject
	    $obj = new Action_Logger($i);

	    // open a couple of messages
	    $log1 = $obj->startAction($msg);
	    $log2 = $obj->startAction($msg);

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->closeAllOpenActions();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($log2->getIsComplete());
	    $this->assertTrue($log1->getIsComplete());

	    $action = $obj->getOpenAction();
	    $this->assertNull($action);

	    // all done
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::startAction
	 * @covers DataSift\Storyplayer\PlayerLib\Action_Logger::getOpenAction
	 */
	public function testGetOpenActionReturnsNullWhenNoActionOpen()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // the message we want to log
	    $expectedMsg = "This is a test message";

	    // our mock output facade
		$output = Mockery::mock("DataSift\Storyplayer\Output");
		$output->shouldReceive('logPhaseActivity')->once()->with($expectedMsg, null);

	    // our mock DI container
	    $i = new stdClass();
	    $i->output = $output;

	    // and, our test subject
	    $obj = new Action_Logger($i);
	    $log = $obj->startAction($expectedMsg);
	    $log->endAction();

	    // ----------------------------------------------------------------
	    // perform the change

	    $action = $obj->getOpenAction();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertNull($action);

	    // all done
	    Mockery::close();
	}

}
