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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OutputLib;

use DataSift\Stone\LogLib\Log;
use DataSift\StoryPlayer\Phases\Phase;
use DataSift\StoryPlayer\PlayerLib\StoryResult;

/**
 * the console plugin loaded when DevMode is active
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DevModeConsolePlugin implements OutputPlugin
{
	protected $verbosityLevel = 0;
	protected $resultStrings  = array();

	public function __construct()
	{
		$this->resultStrings = array (
			StoryResult::PASS => array (
				0 => PHP_EOL . "Result: PASS",
				1 => PHP_EOL . "Result: PASS",
				2 => PHP_EOL . "Result: PASS"
			),
			StoryResult::FAIL => array (
				0 => PHP_EOL . "Result: FAIL",
				1 => PHP_EOL . "Result: FAIL",
				2 => PHP_EOL . "Result: FAIL"
			),
			StoryResult::UNKNOWN => array (
				0 => PHP_EOL . "Result: UNKNOWN",
				1 => PHP_EOL . "Result: UNKNOWN",
				2 => PHP_EOL . "Result: UNKNOWN"
			),
			StoryResult::INCOMPLETE => array (
				0 => PHP_EOL . "Result: INCOMPLETE",
				1 => PHP_EOL . "Result: INCOMPLETE",
				2 => PHP_EOL . "Result: INCOMPLETE"
			),
			StoryResult::BLACKLISTED => array (
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

	public function startStoryplayer($version, $url, $copyright, $license)
	{
		echo <<<EOS
Storyplayer {$version} - {$url}
{$copyright}
{$license}


EOS;
	}

	public function endStoryplayer()
	{

	}

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

	public function endStory(StoryResult $storyResult)
	{
		echo <<<EOS
-------------------------------------------------------------
Final Result

EOS;

		echo $this->resultStrings[$storyResult->storyResult][$this->verbosityLevel] . PHP_EOL;
	}

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

	public function endPhase($phaseName, $phaseResult)
	{
	}

	public function logPhaseActivity($level, $msg)
	{
		// send this to the default logger
		Log::write($level, $msg);
	}

	public function logPhaseError($phaseName, $msg)
	{
		// send this to the default logger
		Log::write(Log::LOG_CRITICAL, $msg);
	}

	public function logPhaseSkipped($phaseName, $msg)
	{
		// send this to the default logger
		Log::write(Log::LOG_NOTICE, $msg);
	}

	public function logCliError($msg)
	{
		echo "*** error: $msg" . PHP_EOL;
	}

	public function logCliWarning($msg)
	{
		echo "*** warning: $msg" . PHP_EOL;
	}

	public function logCliInfo($msg)
	{
		echo $msg . PHP_EOL;
	}

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
