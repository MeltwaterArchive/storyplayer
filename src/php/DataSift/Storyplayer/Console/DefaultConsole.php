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

use DataSift\Storyplayer\OutputLib\CodeFormatter;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\PlayerLib\Phase_Result;
use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Stone\LogLib\Log;

/**
 * the console plugin we use unless the user specifies something else
 *
 * @category  Libraries
 * @package   Storyplayer/Console
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DefaultConsole implements Console
{
	protected $currentPhase;
	protected $phaseNumber = 0;
	protected $phaseMessages = array();
	protected $verbosityLevel = 0;
	protected $resultStrings = array();
	protected $logLevelStrings = [
        Log::LOG_EMERGENCY => "EMERGENCY ",
        Log::LOG_ALERT     => "ALERT     ",
        Log::LOG_CRITICAL  => "CRITICAL  ",
        Log::LOG_ERROR     => "ERROR     ",
        Log::LOG_WARNING   => "WARNING   ",
        Log::LOG_NOTICE    => "NOTICE    ",
        Log::LOG_INFO      => "INFO      ",
        Log::LOG_DEBUG     => "DEBUG     ",
        Log::LOG_TRACE     => "TRACE     ",
	];

	public function __construct()
	{
		$this->resultStrings = array (
			Story_Result::PASS => array (
				0 => '[PASS]',
				1 => PHP_EOL . PHP_EOL . "Result: PASS",
				2 => PHP_EOL . PHP_EOL . "Result: PASS"
			),
			Story_Result::FAIL => array (
				0 => '[FAIL]',
				1 => PHP_EOL . PHP_EOL . "Result: FAIL",
				2 => PHP_EOL . PHP_EOL . "Result: FAIL"
			),
			Story_Result::ERROR => array (
				0 => '[ERROR]',
				1 => PHP_EOL . PHP_EOL . "Result: ERROR",
				2 => PHP_EOL . PHP_EOL . "Result: ERROR"
			),
			Story_Result::INCOMPLETE => array (
				0 => '[INCOMPLETE]',
				1 => PHP_EOL . PHP_EOL . "Result: INCOMPLETE",
				2 => PHP_EOL . PHP_EOL . "Result: INCOMPLETE"
			),
			Story_Result::BLACKLISTED => array (
				0 => '[BLACKLISTED]',
				1 => PHP_EOL . PHP_EOL . "Result: BLACKLISTED",
				2 => PHP_EOL . PHP_EOL . "Result: BLACKLISTED"
			),
		);
	}

	public function setVerbosity($verbosityLevel)
	{
		$this->verbosityLevel = $verbosityLevel;
		if ($this->verbosityLevel > 2) {
			$this->verbosityLevel = 2;
		}
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
		if ($this->verbosityLevel > 0) {
			echo <<<EOS
=============================================================

      Story: {$storyName}
   Category: {$storyCategory}
      Group: {$storyGroup}

Environment: {$envName}
     Device: {$deviceName}

-------------------------------------------------------------


EOS;
		}
		else {
			echo $storyName . ': ';
		}


		// reset the phaseNumber counter
		$this->phaseNumber = 0;
	}

	/**
	 * called when a story finishes
	 *
	 * @param Story_Result $storyResult
	 * @return void
	 */
	public function endStory(Story_Result $storyResult)
	{
		// var_dump($storyResult);

		echo $this->resultStrings[$storyResult->resultCode][$this->verbosityLevel]
		     . ' (' . round($storyResult->durationTime, 2) . ' secs)'
		     . PHP_EOL;

		// do we need to say anything more?
		switch ($storyResult->resultCode)
		{
			case Story_Result::PASS:
			case Story_Result::BLACKLISTED:
				// no, we're happy enough
				return;

			default:
				// everything else is an error of some kind

				// sanity check: we should always have a failedPhase
				if (!$storyResult->failedPhase instanceof Phase_Result) {
					throw new E5xx_MissingFailedPhase();
				}
				$this->showActivityForPhase($storyResult->story, $storyResult->failedPhase);
				break;
		}
	}

	/**
	 * @param Phase_Result $phaseResult
	 */
	protected function showActivityForPhase(Story $story, Phase_Result $phaseResult)
	{
		// what is the phase that we are dealing with?
		$phaseName = $phaseResult->getPhaseName();

		// our final messages to show the user
		$codePoints = [];
		$trace = null;

		// does the phase have an exception?
		$e = $phaseResult->getException();
		if ($e)
		{
			$stackTrace = $e->getTrace();
			foreach ($stackTrace as $stackEntry)
			{
				if (!isset($stackEntry['file'])) {
					continue;
				}

				// do we have any code for this?
				$code = $story->getStoryCodeFor($stackEntry['file'], $stackEntry['line']);
				if (!$code) {
					continue;
				}

				// because we chain multiple method calls on a single line,
				// a PHP stack entry can contain duplicate entries
				//
				// we don't want to show duplicate entries, so we use the
				// filename@line as a key in the array
				$key = $stackEntry['file'] . '@' . $stackEntry['line'];
				if (count($codePoints) > 0 && end($codePoints)['key'] == $key) {
					$codePoint = end($codePoints);
					if ($stackEntry['function'] == '__call') {
						$codePoint['args'] += $stackEntry['args'][1];
					}
					else {
						$codePoint['args'] += $stackEntry['args'];
					}
					$codePoints[count($codePoints) - 1] = $codePoint;
				}
				else {
					$codePoint = $stackEntry;
					$codePoint['code'] = CodeFormatter::formatCode($code);
					$codePoint['key']  = $key;

					// deal with magic
					if ($codePoint['function'] == '__call') {
						$codePoint['args'] = $codePoint['args'][1];
					}

					$codePoints[] = $codePoint;
				}
			}

			$trace = $e->getTraceAsString();
		}

		// let's tell the user what we found
		echo <<<EOS

=============================================================
DETAILED ERROR REPORT
----------------------------------------


EOS;

		echo "The story failed in the " . $phaseName . " phase." . PHP_EOL;
		if (count($codePoints) > 0) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "The story was executing this Prose code when it failed:"
			     . PHP_EOL;

			$codePoints = array_reverse($codePoints);
			foreach ($codePoints as $codePoint) {
				echo PHP_EOL . str_repeat(' ', 4) . $codePoint['file'] . '@' . $codePoint['line'] . ':' . PHP_EOL . PHP_EOL;
				echo CodeFormatter::indentBySpaces($codePoint['code'], 8) . PHP_EOL;

				if (isset($codePoint['args']) && count($codePoint['args'])) {
					echo PHP_EOL . '        Arguments:' . PHP_EOL;
					foreach ($codePoint['args'] as $key => $arg) {
						ob_start();
						var_dump($arg);
						$printableArg = ob_get_contents();
						ob_end_clean();

						// how many lines do we have?
						$lines = explode(PHP_EOL, $printableArg);
						$maxLength = 100;
						if (count($lines) > $maxLength) {
							$printableArg = '';
							for ($i = 0; $i < $maxLength; $i++) {
								$printableArg .= $lines[$i] . PHP_EOL;
							}
							$printableArg .= '...' . PHP_EOL;
						}

						echo PHP_EOL . CodeFormatter::indentBySpaces($printableArg, 12);
					}
				}
			}
		}
		if (isset($this->phaseMessages[$phaseName])) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "This is the detailed output from the {$phaseName} phase:"
			     . PHP_EOL . PHP_EOL;

			foreach ($this->phaseMessages[$phaseName] as $msg) {
				echo "[" . date("Y-m-d H:i:s", $msg['ts']) . "] "
				     . $this->logLevelStrings[$msg['level']]
                     . $msg['text'] . PHP_EOL;
			}
		}

		if ($trace) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "This is the stack trace for this failure:"
			     . PHP_EOL . PHP_EOL
			     . CodeFormatter::indentBySpaces($trace, 4) . PHP_EOL;
		}

		// all done
		echo <<< EOS

----------------------------------------
END OF ERROR REPORT
=============================================================

EOS;
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
		// make sure we can keep track of what the phase is doing
		$this->phaseMessages[$phaseName] = [];
		$this->currentPhase = $phaseName;

		// we're only interested in telling the user about the
		// phases of a story
		if ($phaseType !== Phase::STORY_PHASE) {
			return;
		}

		// increment our internal counter
		$this->phaseNumber++;

		// tell the user which phase we're doing
		if ($this->verbosityLevel > 0) {
			echo $phaseName . ': ';
		}
		else {
			echo $this->phaseNumber;
		}
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
		// we're only interested in telling the user about the
		// phases of a story
		if ($phaseType !== Phase::STORY_PHASE) {
			return;
		}

		if ($this->verbosityLevel > 0) {
			echo PHP_EOL;
		}
		else {
			echo ' ';
		}
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
		// keep track of what was attempted, in case we need to show
		// the user what was attempted
		$this->phaseMessages[$this->currentPhase][] = [
			'ts'    => time(),
			'level' => $level,
			'text'  => $msg
		];

		// show the user that *something* happened
		echo ".";
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
		// we have to show this now, and save it for final output later
		echo "e";

		$this->phaseMessages[$this->currentPhase][] = [
			'ts'    => time(),
			'level' => Log::LOG_CRITICAL,
			'text'  => $msg
		];
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
		// we have to show this now, and save it for final output later
		echo "s";

		// $this->phaseMessages[$phaseName] = $msg;
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
		// this is a no-op for us
	}
}
