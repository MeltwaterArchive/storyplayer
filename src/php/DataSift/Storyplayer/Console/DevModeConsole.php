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
 * @category  Libraries
 * @package   Storyplayer/Console
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Console;

use DataSift\Stone\LogLib\Log;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\PlayerLib\Story_Result;

/**
 * the console plugin loaded when DevMode is active
 *
 * @category  Libraries
 * @package   Storyplayer/Console
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DevModeConsole implements Console
{
	protected $verbosityLevel = 0;
	protected $resultStrings  = array();

	public function __construct()
	{
		$this->resultStrings = array (
			Story_Result::PASS => array (
				0 => PHP_EOL . "Result: PASS",
				1 => PHP_EOL . "Result: PASS",
				2 => PHP_EOL . "Result: PASS"
			),
			Story_Result::FAIL => array (
				0 => PHP_EOL . "Result: FAIL",
				1 => PHP_EOL . "Result: FAIL",
				2 => PHP_EOL . "Result: FAIL"
			),
			Story_Result::ERROR => array (
				0 => PHP_EOL . "Result: ERROR",
				1 => PHP_EOL . "Result: ERROR",
				2 => PHP_EOL . "Result: ERROR"
			),
			Story_Result::INCOMPLETE => array (
				0 => PHP_EOL . "Result: INCOMPLETE",
				1 => PHP_EOL . "Result: INCOMPLETE",
				2 => PHP_EOL . "Result: INCOMPLETE"
			),
			Story_Result::BLACKLISTED => array (
				0 => PHP_EOL . "Result: BLACKLISTED",
				1 => PHP_EOL . "Result: BLACKLISTED",
				2 => PHP_EOL . "Result: BLACKLISTED"
			),
		);
	}

	public function setVerbosity($verbosityLevel)
	{
		$this->verbosityLevel = $verbosityLevel;
	}

	/**
	 * called when storyplayer starts
	 *
	 * @param string $version
	 * @param string $url
	 * @param string $copyright
	 * @param string $license
	 * @return void
	 */
	public function startStoryplayer($version, $url, $copyright, $license)
	{
		echo <<<EOS
Storyplayer {$version} - {$url}
{$copyright}
{$license}


EOS;
	}

	/**
	 * called when Storyplayer exits
	 *
	 * @return void
	 */
	public function endStoryplayer()
	{

	}

	/**
	 * called when a new story starts
	 *
	 * a single copy of Storyplayer may execute multiple tests
	 *
	 * @param string $storyName
	 * @param string $storyCategory
	 * @param string $storyGroup
	 * @param string $envName
	 * @param string $deviceName
	 * @return void
	 */
	public function startStory($storyName, $storyCategory, $storyGroup, $envName, $deviceName)
	{
		echo <<<EOS
=============================================================

      Story: {$storyName}
   Category: {$storyCategory}
      Group: {$storyGroup}

Environment: {$envName}
     Device: {$deviceName}

EOS;
	}

	/**
	 * called when a story finishes
	 *
	 * @param Story_Result $storyResult
	 * @return void
	 */
	public function endStory(Story_Result $storyResult)
	{
		echo <<<EOS

-------------------------------------------------------------
Final Result

EOS;

		echo $this->resultStrings[$storyResult->resultCode][$this->verbosityLevel] . PHP_EOL;
		echo 'Duration: ' . round($storyResult->durationTime, 2) . ' secs' . PHP_EOL;
	}

	/**
	 * called when a story starts a new phase
	 *
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function startPhase($phaseName, $phaseType)
	{
		// we only announce story phases
		if ($phaseType != Phase::STORY_PHASE) {
			return;
		}

		echo PHP_EOL;
		echo "-------------------------------------------------------------" . PHP_EOL;
		echo "Now performing: $phaseName" . PHP_EOL;
		echo PHP_EOL;
	}

	/**
	 * called when a story ends a phase
	 *
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function endPhase($phaseName, $phaseType)
	{
		// this is a no-op for us
	}

	/**
	 * called when a story logs an action
	 *
	 * @param integer $level
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseActivity($level, $msg)
	{
		// send this to the default logger
		Log::write($level, $msg);
	}

	/**
	 * called when a story logs an error
	 *
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseError($phaseName, $msg)
	{
		// send this to the default logger
		Log::write(Log::LOG_CRITICAL, $msg);
	}

	/**
	 * called when a story is skipped
	 *
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseSkipped($phaseName, $msg)
	{
		// send this to the default logger
		Log::write(Log::LOG_NOTICE, $msg);
	}

	/**
	 * called when the outer CLI shell encounters a fatal error
	 *
	 * @param  string $msg
	 *         the error message to show the user
	 *
	 * @return void
	 */
	public function logCliError($msg)
	{
		echo "*** error: $msg" . PHP_EOL;
	}

	/**
	 *
	 * @param  string $msg
	 * @param  Exception $e
	 * @return void
	 */
	public function logCliErrorWithException($msg, $e)
	{
		echo "*** error: $msg" . PHP_EOL . PHP_EOL
		     . "This was caused by an unexpected exception " . get_class($e) . PHP_EOL . PHP_EOL
		     . $e->getTraceAsString();
	}

	/**
	 * called when the outer CLI shell needs to publish a warning
	 *
	 * @param  string $msg
	 *         the warning message to show the user
	 *
	 * @return void
	 */
	public function logCliWarning($msg)
	{
		echo "*** warning: $msg" . PHP_EOL;
	}

	/**
	 * called when the outer CLI shell needs to tell the user something
	 *
	 * @param  string $msg
	 *         the message to show the user
	 *
	 * @return void
	 */
	public function logCliInfo($msg)
	{
		echo $msg . PHP_EOL;
	}

	/**
	 * an alternative to using PHP's built-in var_dump()
	 *
	 * @param  string $name
	 *         a human-readable name to describe $var
	 *
	 * @param  mixed $var
	 *         the variable to dump
	 *
	 * @return void
	 */
	public function logVardump($name, $var)
	{
		// grab the output buffer
		ob_start();

		// dump the variable
		var_dump($var);

		// get the contents of the output buffer
		$output = ob_get_contents();

		// we're done with the output buffer
		ob_end_clean();

		// send the output to the default logger
		Log::write(Log::LOG_DEBUG, $name . ' => ' . $output);
	}
}
