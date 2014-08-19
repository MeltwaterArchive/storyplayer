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
 * @package     Storyplayer
 * @subpackage  Prose
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer;

use Exception;
use PHPUnit_Framework_TestCase;
use DataSift\Storyplayer\Console\DefaultConsole;
use DataSift\Storyplayer\Console\DevModeConsole;
use DataSift\Storyplayer\OutputLib\OutputWriter;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\Phases\ExampleStoryPhase;
use DataSift\Storyplayer\PlayerLib\Phase_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use Mockery;

class OutputTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\Output::__construct()
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new Output();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Output);
	}

	/**
	 * @covers DataSift\Storyplayer\Output::__construct()
	 * @covers DataSift\Storyplayer\Output::getActiveConsolePlugin()
	 */
	public function testStartsWithTheDefaultConsole()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new Output();

	    // ----------------------------------------------------------------
	    // test the results

	    $console = $obj->getActiveConsolePlugin();
	    $this->assertTrue($console instanceof DefaultConsole);
	}

	/**
	 * @covers DataSift\Storyplayer\Output::__construct()
	 * @covers DataSift\Storyplayer\Output::getActiveConsolePlugin()
	 */
	public function testTheDefaultConsoleStartsConnectedToStdout()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new Output();

	    // ----------------------------------------------------------------
	    // test the results

	    $console = $obj->getActiveConsolePlugin();
	    $this->assertTrue($console instanceof DefaultConsole);

	    $writer = $console->getWriter();
	    $this->assertTrue($writer instanceof OutputWriter);
	    $this->assertTrue($writer->getIsUsingStdout());
	}

	/**
	 * @covers DataSift\Storyplayer\Output::usePluginAsConsole()
	 * @covers DataSift\Storyplayer\Output::getActiveConsolePlugin()
	 */
	public function testCanSwitchToTheDefaultConsole()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();

	    // ----------------------------------------------------------------
	    // perform the change

	    $console = new DefaultConsole();
	    $obj->usePluginAsConsole($console);

	    // ----------------------------------------------------------------
	    // test the results

	    $activeConsole = $obj->getActiveConsolePlugin();
	    $this->assertSame($console, $activeConsole);
	}

	/**
	 * @covers DataSift\Storyplayer\Output::usePluginAsConsole()
	 * @covers DataSift\Storyplayer\Output::getActiveConsolePlugin()
	 */
	public function testCanSwitchToTheDevModeConsole()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();

	    // ----------------------------------------------------------------
	    // perform the change

	    $console = new DevModeConsole();
	    $obj->usePluginAsConsole($console);

	    // ----------------------------------------------------------------
	    // test the results

	    $activeConsole = $obj->getActiveConsolePlugin();
	    $this->assertSame($console, $activeConsole);
	}

	/**
	 * @covers DataSift\Storyplayer\Output::usePluginAsConsole()
	 */
	public function testCanSwitchToAnyConsole()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();
	    $console = Mockery::mock('DataSift\Storyplayer\Console\Console');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->usePluginAsConsole($console);

	    // ----------------------------------------------------------------
	    // test the results

	    $activeConsole = $obj->getActiveConsolePlugin();
	    $this->assertSame($console, $activeConsole);
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::usePluginInSlot()
	 * @covers DataSift\Storyplayer\Output::getActivePluginInSlot()
	 */
	public function testCanAddAnOutputPlugin()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();
	    $slotName = "unit-test";
	    $origPlugin = Mockery::mock('DataSift\Storyplayer\OutputLib\OutputPlugin');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->usePluginInSlot($origPlugin, $slotName);

	    // ----------------------------------------------------------------
	    // test the results

	    $activePlugin = $obj->getActivePluginInSlot($slotName);
	    $this->assertSame($origPlugin, $activePlugin);
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::getActivePluginInSlot()
	 */
	public function testReturnsNullIfNoActivePluginInSlot()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();
	    $slotName = 'unit-test';

	    // ----------------------------------------------------------------
	    // perform the change

	    // ----------------------------------------------------------------
	    // test the results

	    $activePlugin = $obj->getActivePluginInSlot($slotName);
	    $this->assertNull($activePlugin);
	}

	/**
	 * @covers DataSift\Storyplayer\Output::getPlugins()
	 */
	public function testCanGetAllActivePlugins()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $obj = new Output();
	    $slotName = 'unit-test';
	    $origPlugin = Mockery::mock('DataSift\Storyplayer\OutputLib\OutputPlugin');
	    $obj->usePluginInSlot($origPlugin, $slotName);
	    $console = $obj->getActiveConsolePlugin();

	    // ----------------------------------------------------------------
	    // perform the change

	    $plugins = $obj->getPlugins();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue(is_array($plugins));
	    $this->assertEquals(2, count($plugins));
	    $this->assertSame($console, $plugins['console']);
	    $this->assertSame($origPlugin, $plugins[$slotName]);
	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::resetSilentMode()
	 */
	public function testCanResetSilentMode()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('resetSilentMode')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('resetSilentMode')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->resetSilentMode();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::setSilentMode()
	 */
	public function testCanSetSilentMode()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('setSilentMode')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('setSilentMode')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setSilentMode();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::disableColourSupport()
	 */
	public function testCanDisableColourSupport()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('disableColourSupport')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('disableColourSupport')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->disableColourSupport();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::enforceColourSupport()
	 */
	public function testCanEnforceColourSupport()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('enforceColourSupport')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('enforceColourSupport')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->enforceColourSupport();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::enableColourSupport()
	 */
	public function testCanEnableColourSupport()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('enableColourSupport')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('enableColourSupport')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->enableColourSupport();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::startStoryplayer()
	 */
	public function testCanStartStoryplayer()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$version = "6.6.6";
		$url = "http://notarealurl.topleveldomain";
		$copyright = "a copyright string";
		$license = "a license string";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('startStoryplayer')->once()->with($version, $url, $copyright, $license);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('startStoryplayer')->once()->with($version, $url, $copyright, $license);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->startStoryplayer($version, $url, $copyright, $license);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::endStoryplayer()
	 */
	public function testCanEndStoryplayer()
	{
	    // ----------------------------------------------------------------
	    // setup the test

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('endStoryplayer')->once();
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('endStoryplayer')->once();

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endStoryplayer();

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::startPhaseGroup()
	 */
	public function testCanStartPhaseGroup()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$activity = "starting";
		$name = "unit test";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('startPhaseGroup')->once()->with($activity, $name);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('startPhaseGroup')->once()->with($activity, $name);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->startPhaseGroup($activity, $name);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::endPhaseGroup()
	 */
	public function testCanEndPhaseGroup()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$result = new PhaseGroup_Result();

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('endPhaseGroup')->once()->with($result);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('endPhaseGroup')->once()->with($result);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endPhaseGroup($result);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::startPhase()
	 */
	public function testCanStartPhase()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
		$phase = new ExampleStoryPhase($st);

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('startPhase')->once()->with($phase);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('startPhase')->once()->with($phase);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->startPhase($phase);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::endPhase()
	 */
	public function testCanEndPhase()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
		$phase = new ExampleStoryPhase($st);
		$result = new Phase_Result('unit-test');

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('endPhase')->once()->with($phase, $result);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('endPhase')->once()->with($phase, $result);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->endPhase($phase, $result);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logPhaseActivity()
	 */
	public function testCanLogPhaseActivityWithNoCodeLine()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "a unit-test is running ... do not be alarmed!";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logPhaseActivity')->once()->with($msg, null);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logPhaseActivity')->once()->with($msg, null);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logPhaseActivity($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logPhaseActivity()
	 */
	public function testCanLogPhaseActivityWithCodeLine()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "a unit-test is running ... do not be alarmed!";
		$codeLine = [
			"file" => "unit-test.php",
			"line" => 666,
			"code" => "\$st->fromUnitTests()->testAllTheThings();"
		];

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logPhaseActivity')->once()->with($msg, $codeLine);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logPhaseActivity')->once()->with($msg, $codeLine);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logPhaseActivity($msg, $codeLine);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logPhaseError()
	 */
	public function testCanLogPhaseError()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$phaseName = "a unit-test";
		$msg = "something went right ... wait, what?!?";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logPhaseError')->once()->with($phaseName, $msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logPhaseError')->once()->with($phaseName, $msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logPhaseError($phaseName, $msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logPhaseSkipped()
	 */
	public function testCanLogPhaseSkipped()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$phaseName = "a unit-test";
		$msg = "we interrupt your regular entertainment because we are skipping over it ;)";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logPhaseSkipped')->once()->with($phaseName, $msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logPhaseSkipped')->once()->with($phaseName, $msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logPhaseSkipped($phaseName, $msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logPhaseCodeLine()
	 */
	public function testCanLogPhaseCodeLine()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$codeLine = [
			"file" => "unit-test.php",
			"line" => 666,
			"code" => "\$st->fromUnitTests()->testAllTheThings();"
		];

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logPhaseCodeLine')->once()->with($codeLine);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logPhaseCodeLine')->once()->with($codeLine);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logPhaseCodeLine($codeLine);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logCliError()
	 */
	public function testCanLogCliError()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "this is an emergency, damnit!";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logCliError')->once()->with($msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logCliError')->once()->with($msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logCliError($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logCliErrorWithException()
	 */
	public function testCanLogCliErrorWithException()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "this is an emergency, damnit!";
		$e = new Exception();

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logCliErrorWithException')->once()->with($msg, $e);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logCliErrorWithException')->once()->with($msg, $e);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logCliErrorWithException($msg, $e);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logCliWarning()
	 */
	public function testCanLogCliWarning()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "this might be an emergency, damnit!";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logCliWarning')->once()->with($msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logCliWarning')->once()->with($msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logCliWarning($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logCliInfo()
	 */
	public function testCanLogCliInfo()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$msg = "there's nothing to see here ... move along, move along";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logCliInfo')->once()->with($msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logCliInfo')->once()->with($msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logCliInfo($msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

	/**
	 * @covers DataSift\Storyplayer\Output::logVardump()
	 */
	public function testCanLogVardump()
	{
	    // ----------------------------------------------------------------
	    // setup the test

		$name = 'msg';
		$msg = "this is an emergency, damnit!";

	    $plugin1 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	   	$plugin1->shouldReceive('logVardump')->once()->with($name, $msg);
	    $plugin2 = Mockery::mock("DataSift\Storyplayer\OutputLib\OutputPlugin");
	    $plugin2->shouldReceive('logVardump')->once()->with($name, $msg);

	    $obj = new Output();
	    $obj->usePluginInSlot($plugin1, "console");
	    $obj->usePluginInSlot($plugin2, "slot1");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->logVardump($name, $msg);

	    // ----------------------------------------------------------------
	    // test the results

	    Mockery::close();
	}

}