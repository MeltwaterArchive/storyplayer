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

	public function setVerbosity($verbosityLevel)
	{
		$this->verbosityLevel = $verbosityLevel;
	}

	public function startStoryplayer()
	{

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
		var_dump($storyResult);
		return;

		$resultMessage = '';
		switch ($actionShouldWork) {
			case self::PREDICT_SUCCESS:
				$resultMessage = 'expected: SUCCESS         ;';
				break;

			case self::PREDICT_FAIL:
				$resultMessage = 'expected: FAIL            ;';
				break;

			case self::PREDICT_INCOMPLETE:
				$resultMessage = 'expected: DID NOT COMPLETE;';
				break;

			case self::PREDICT_UNKNOWN:
			default:
				$resultMessage = 'expected: UNKNOWN :(      ;';
				break;
		}

		switch ($actionResult) {
			case self::ACTION_COMPLETED:
				$resultMessage .= ' action: COMPLETED ;';
				break;

			case self::ACTION_FAILED:
				$resultMessage .= ' action: FAILED    ;';
				break;

			case self::ACTION_INCOMPLETE:
				$resultMessage .= ' action: INCOMPLETE;';
				break;

			case self::ACTION_HASNOACTIONS:
				$resultMessage .= ' action: NO ACTION ;';
				break;

			default:
				$resultMessage .= ' action: UNKNOWN   ;';
		}

		switch ($actionWorked) {
			case self::INSPECT_SUCCESS:
				$resultMessage .= ' actual: SUCCESS         ;';
				break;

			case self::INSPECT_FAIL:
				$resultMessage .= ' actual: FAIL            ;';
				break;

			case self::INSPECT_INCOMPLETE:
				$resultMessage .= ' actual: DID NOT COMPLETE;';
				break;

			case self::INSPECT_UNKNOWN:
			default:
				$resultMessage .= ' actual: UNKNOWN :(      ;';
				break;
		}

		switch($result->storyResult)
		{
			case StoryResult::RESULT_PASS:
				$resultMessage .= ' result: PASS';
				break;

			case StoryResult::RESULT_FAIL:
				$resultMessage .= ' result: FAIL';
				break;

			case StoryResult::RESULT_BLACKLISTED:
				$resultMessage = 'result: DID NOT RUN (unsafe environment)';
				break;

			case StoryResult::RESULT_UNKNOWN:
			default:
				$resultMessage .= ' result: UNKNOWN';
		}

		// tell the user what happened
		Log::write(Log::LOG_NOTICE, $resultMessage);

	}

	public function startStoryPhase($phaseName, $phaseType)
	{
		echo PHP_EOL;
		echo "-------------------------------------------------------------" . PHP_EOL;
		echo "Now performing: $phaseName" . PHP_EOL;
		echo PHP_EOL;
	}

	public function endStoryPhase()
	{

	}

	public function logStoryActivity($level, $msg)
	{
		// send this to the default logger
		Log::write($level, $msg);
	}

	public function logStoryError($phaseName, $msg)
	{
		// send this to the default logger
		Log::write(Log::LOG_CRITICAL, $msg);
	}

	public function logStorySkipped($phaseName, $msg)
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
}
