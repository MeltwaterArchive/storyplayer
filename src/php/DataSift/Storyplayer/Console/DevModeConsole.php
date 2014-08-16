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

use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use DataSift\Storyplayer\PlayerLib\Phase_Result;
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
class DevModeConsole extends Console
{
	protected $resultStrings  = array();

	/**
	 * are we running totally silently?
	 * @var boolean
	 */
	protected $silentActivity = false;

	public function __construct()
	{
		parent::__construct();

		$this->resultStrings = [
			PhaseGroup_Result::OKAY        => "Result: PASS",
			PhaseGroup_Result::FAIL        => "Result: FAIL",
			PhaseGroup_Result::ERROR       => "Result: ERROR",
			PhaseGroup_Result::INCOMPLETE  => "Result: INCOMPLETE",
			PhaseGroup_Result::BLACKLISTED => "Result: BLACKLISTED",
			PhaseGroup_Result::SKIPPED     => "Result: SKIPPED",
		];
	}

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
		$this->write("=============================================================" . PHP_EOL, $this->writer->commentStyle);
		$this->write($activity . ' ', $this->writer->activityStyle);
		$this->write($name . PHP_EOL, $this->writer->nameStyle);
		//$this->write('-------------------------------------------------------------' . PHP_EOL, $this->writer->commentStyle);
		//$this->write(PHP_EOL);
	}

	public function endPhaseGroup($result)
	{
		$resultString = $result->getResultString();
		$duration     = $result->getDuration();

		$this->write(PHP_EOL);
		//$this->write('-------------------------------------------------------------' . PHP_EOL, $this->writer->commentStyle);
		$this->write('----' . PHP_EOL, $this->writer->commentStyle);
		$this->write("Result: ");
		if ($result->getPhaseGroupSucceeded()){
			$this->write($resultString, $this->writer->successStyle);
		}
		else if ($result->getPhaseGroupFailed()) {
			$this->write($resultString, $this->writer->failStyle);
		}
		else {
			$this->write($resultString, $this->writer->skippedStyle);
		}

		$this->write(" (", $this->writer->punctuationStyle);
		$this->writeDuration($duration);
		$this->write(")" . PHP_EOL, $this->writer->punctuationStyle);
		$this->write('-------------------------------------------------------------' . PHP_EOL . PHP_EOL, $this->writer->commentStyle);

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

		// our whitelist of phases that we want to announce
		static $announcedPhases = [
			Phase::STORY_PHASE => true,
			Phase::STORY_SUPPORT_PHASE => true,
			Phase::INFRASTRUCTURE_PHASE => true
		];

		// we only announced the whitelisted types
		if (!isset($announcedPhases[$phaseType]) || !$announcedPhases[$phaseType]) {
			return;
		}

		$this->write(PHP_EOL);
		$this->write("----" . PHP_EOL, $this->writer->commentStyle);
//		$this->write("-------------------------------------------------------------" . PHP_EOL, $this->writer->commentStyle);
		$this->write("Running phase ", $this->writer->activityStyle);
		if ($phaseSeqNo) {
			$this->write("$phaseSeqNo. ");
		}
		$this->write("$phaseName" . PHP_EOL);
		$this->write(PHP_EOL);
		//$this->write("  -----------------------------------------------------------" . PHP_EOL, $this->writer->commentStyle);
	}

	/**
	 * called when a story ends a phase
	 *
	 * @return void
	 */
	public function endPhase($phase, $phaseResult)
	{
		// this is a no-op for us
	}

	protected function logActivity($message)
	{
        $now = date('Y-m-d H:i:s', time());

        $this->write('[', $this->writer->punctuationStyle);
        $this->write($now, $this->writer->timeStyle);
        $this->write('] ', $this->writer->punctuationStyle);
        $this->write(rtrim($message) . PHP_EOL);
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
		if (!$this->silentActivity) {
			$this->logActivity($msg);
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
		$this->logActivity($msg);
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
		$this->logActivity($msg);
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
		// grab the output buffer
		ob_start();

		// dump the variable
		var_dump($var);

		// get the contents of the output buffer
		$output = ob_get_contents();

		// we're done with the output buffer
		ob_end_clean();

		// send the output to the default logger
		$this->write($name . ' => ' . $output);
	}
}
