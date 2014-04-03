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

use DataSift\StoryPlayer\Phases\Phase;
use DataSift\StoryPlayer\PlayerLib\StoryResult;

/**
 * the console plugin we use unless the user specifies something else
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DefaultConsolePlugin implements OutputPlugin
{
	protected $currentPhase;
	protected $phaseNumber = 0;
	protected $phaseErrors = array();

	protected $phaseMessages = array();

	protected $verbosityLevel = 0;

	protected $resultStrings = array();

	public function __construct()
	{
		$this->resultStrings = array (
			StoryResult::PASS => array (
				0 => '[PASS]',
				1 => PHP_EOL . PHP_EOL . "Result: PASS",
				2 => PHP_EOL . PHP_EOL . "Result: PASS"
			),
			StoryResult::FAIL => array (
				0 => '[FAIL]',
				1 => PHP_EOL . PHP_EOL . "Result: FAIL",
				2 => PHP_EOL . PHP_EOL . "Result: FAIL"
			),
			StoryResult::UNKNOWN => array (
				0 => '[UNKNOWN]',
				1 => PHP_EOL . PHP_EOL . "Result: UNKNOWN",
				2 => PHP_EOL . PHP_EOL . "Result: UNKNOWN"
			),
			StoryResult::INCOMPLETE => array (
				0 => '[INCOMPLETE]',
				1 => PHP_EOL . PHP_EOL . "Result: INCOMPLETE",
				2 => PHP_EOL . PHP_EOL . "Result: INCOMPLETE"
			),
			StoryResult::BLACKLISTED => array (
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
	 * @param StoryResult $storyResult
	 * @return void
	 */
	public function endStory(StoryResult $storyResult)
	{
		// var_dump($storyResult);

		echo $this->resultStrings[$storyResult->storyResult][$this->verbosityLevel] . PHP_EOL;

		if (count($this->phaseErrors) > 0) {
			foreach ($this->phaseErrors as $phaseName => $msg)
			{
				// what activity was the phase doing?
				$this->showActivityForPhase($phaseName);

				// what was the final error message?
				echo $msg . PHP_EOL;
			}

			// finish with a blank line so that any subsequent story is
			// easier to see
			echo PHP_EOL;
		}
	}

	/**
	 * @param string $phaseName
	 */
	protected function showActivityForPhase($phaseName)
	{
		if (!isset($this->phaseMessages[$phaseName]) || !count($this->phaseMessages[$phaseName])) {
			// we have nothing to show
			return;
		}

		// tell the world
		echo PHP_EOL . "The story failed in the {$phaseName} phase:" . PHP_EOL . PHP_EOL;

		// show the activity of the phase
		foreach ($this->phaseMessages[$phaseName] as $msg) {
			echo "[" . date("Y-m-d H:i:s", $msg['ts']) . "] "
			     . $msg['text'] . PHP_EOL;
		}

		// leave a blank line afterwards
		echo PHP_EOL;
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

		$this->phaseErrors[$phaseName] = $msg;
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

		// $this->phaseErrors[$phaseName] = $msg;
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
