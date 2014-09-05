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
 * @package     Storyplayer/CommandLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\CommandLib;

use Exception;
use Mockery;
use PHPUnit_Framework_TestCase;

class CommandRunnerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers1 DataSift\Storyplayer\CommandLib\CommandRunner::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new CommandRunner();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof CommandRunner);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCanRunCommands()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "true";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCommandMustBeAString()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");

		// a list of invalid commands to try
		$helperCmds = [
			1,
			5.9,
			null,
			[ 'fred' ],
			(object)['fred' => 'harry'],
		];

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($helperCmds as $helperCmd) {
	    	try {
		    	$caughtException = false;
		    	$obj->runSilently($st, $cmd);
		    }
		    catch (Exception $e) {
		    	$caughtException = true;
		    }
		    $this->assertTrue($caughtException);
		}
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCapturesStdoutFromTheCommand()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();
	    $log->shouldReceive('captureOutput')->once();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "echo fred";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	    $this->assertEquals("fred" . PHP_EOL, $result->output);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCapturesStderrFromTheCommand()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();
	    $log->shouldReceive('captureOutput')->once();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "echo fred >&2";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	    $this->assertEquals("fred" . PHP_EOL, $result->output);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCapturesBothStdoutAndStderrFromTheCommand()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();
	    $log->shouldReceive('captureOutput')->twice();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "echo fred ; echo harry >&2";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	    $this->assertEquals("fred" . PHP_EOL . "harry" . PHP_EOL, $result->output);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testCapturesReturnCodeFromTheCommand()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();
	    $log->shouldReceive('captureOutput')->once();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "echo fred ; exit 1";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	    $this->assertEquals(1, $result->returnCode);
	}

	/**
	 * @covers DataSift\Storyplayer\CommandLib\CommandRunner::runSilently
	 */
	public function testReturns127IfTheCommandDoesNotExist()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// our test subject
		$obj = new CommandRunner();

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();
	    $log->shouldReceive('captureOutput')->once();

		// our mocked $st
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);

	    // our example command
		$helperCmd = "/fred/alice/this_command_does_not_exist";

	    // ----------------------------------------------------------------
	    // perform the change

		$result = $obj->runSilently($st, $helperCmd);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($result instanceof CommandResult);
	    $this->assertEquals(127, $result->returnCode);
	}
}
