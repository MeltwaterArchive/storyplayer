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
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\PlayerLib\Story;

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
class DefaultConsole extends Console
{
	protected $currentPhase;
	protected $phaseNumber = 0;
	protected $phaseMessages = array();

	/**
	 * a list of the results we have received from stories
	 * @var array
	 */
	protected $storyResults = [];

	/**
	 * are we running totally silently?
	 * @var boolean
	 */
	protected $silentActivity = false;

	public function resetSilent()
	{
		$this->silentActivity = false;
	}

	public function setSilent()
	{
		$this->silentActivity = true;
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
		$this->write("Storyplayer {$version}", $this->writer->highlightStyle);
		$this->write(" - ");
		$this->write($url, $this->writer->urlStyle);
		$this->write(PHP_EOL);
		$this->write($copyright . PHP_EOL);
		$this->write($license . PHP_EOL . PHP_EOL);
	}

	/**
	 * called when we start a new set of phases
	 *
	 * @param  string $name
	 * @return void
	 */
	public function startPhaseGroup($activity, $name)
	{
		$this->write($activity . ' ', $this->writer->activityStyle);
		$this->write($name, $this->writer->nameStyle);
		$this->write(': ', $this->writer->punctuationStyle);
	}

	public function endPhaseGroup($result)
	{
		$this->write(' [', $this->writer->punctuationStyle);
		if ($result->getPhaseGroupSucceeded()) {
			$style = $this->writer->successStyle;
		}
		else if ($result->getPhaseGroupFailed()) {
			$style = $this->writer->failStyle;
		}
		else {
			$style = $this->writer->skippedStyle;
		}
		$this->write($result->getResultString(), $style);
		$this->write('] (', $this->writer->punctuationStyle);
		$this->writeDuration($result->getDuration());
		$this->write(')' . PHP_EOL, $this->writer->punctuationStyle);

		// remember the result for the final report
		//
		// we have to clone as the result object apparently changes
		// afterwards. no idea why (yet)
		$this->results[] = clone $result;
	}

	/**
	 * called when a story starts a new phase
	 *
	 * @return void
	 */
	public function startPhase($phase)
	{
		// shorthand
		$phaseName  = $phase->getPhaseName();
		$phaseType  = $phase->getPhaseType();
		$phaseSeqNo = $phase->getPhaseSequenceNo();

		// make sure we can keep track of what the phase is doing
		$phaseName = $phase->getPhaseName();
		$this->phaseMessages[$phaseName] = [];
		$this->currentPhase = $phaseName;

		// we're only interested in telling the user about the
		// phases of a story
		if ($phaseType !== Phase::STORY_PHASE) {
			return;
		}

		// tell the user
		$this->write($phaseSeqNo, $this->writer->miniPhaseNameStyle);
	}

	/**
	 * called when a story ends a phase
	 *
	 * @return void
	 */
	public function endPhase($phase, $phaseResult)
	{
		// shorthand
		$phaseType = $phase->getPhaseType();

		// we're only interested in telling the user about the
		// phases of a story
		if ($phaseType !== Phase::STORY_PHASE) {
			return;
		}

		// tell the user
		$this->write(' ');
	}

	/**
	 * called when a story logs an action
	 *
	 * @param integer $level
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseActivity($msg)
	{
		// keep track of what was attempted, in case we need to show
		// the user what was attempted
		$this->phaseMessages[$this->currentPhase][] = [
			'ts'    => time(),
			'text'  => $msg
		];

		// show the user that *something* happened
		if (!$this->silentActivity) {
			$this->write(".", $this->writer->miniActivityStyle);
		}
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
		$this->write("e");

		$this->phaseMessages[$this->currentPhase][] = [
			'ts'    => time(),
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
		$this->write("s", $this->writer->skippedStyle);

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
		$this->write("*** error: $msg" . PHP_EOL);
	}

	/**
	 *
	 * @param  string $msg
	 * @param  Exception $e
	 * @return void
	 */
	public function logCliErrorWithException($msg, $e)
	{
		$this->write("*** error: $msg" . PHP_EOL . PHP_EOL
		     . "This was caused by an unexpected exception " . get_class($e) . PHP_EOL . PHP_EOL
		     . $e->getTraceAsString());
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
		$this->write("*** warning: $msg" . PHP_EOL);
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
		$this->write($msg . PHP_EOL);
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
