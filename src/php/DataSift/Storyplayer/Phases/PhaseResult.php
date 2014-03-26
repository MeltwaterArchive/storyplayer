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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

use DataSift\StoryPlayer\PlayerLib\PhasesPlayer;

/**
 * tracks the result from a single phase
 *
 * a result is a little more than just a PASS/FAIL:
 *
 * 1. we need to know what happened during this phase
 * 2. we need to know how this affects the playing of the story
 * 3. we need to know if there are any other phases we need to execute
 *    *because* we have executed this phase
 * 4. we need to know if there's a message to pass on to the end-user
 *
 * perhaps it was much easier when we just hard-coded all of this?
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class PhaseResult
{
	protected $message;
	protected $nextAction;
	protected $pairedPhases = array();
	protected $result;

	const MIN_RESULT = 1;
	const MAX_RESULT = 5;

	const COMPLETED    = 1;
	// success is an alias for completed!
	const SUCCEEDED    = 1;
	const FAILED       = 2;
	const INCOMPLETE   = 3;
	const HASNOACTIONS = 4;
	const SKIPPED      = 5;
	const BLACKLISTED  = 6;

	public function hasMessage()
	{
		if (isset($this->message)) {
			return true;
		}

		return false;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function getPhaseResult()
	{
		return $this->result;
	}

	public function getPhaseCompleted()
	{
		if ($this->result == self::COMPLETED) {
			return true;
		}

		return false;
	}

	public function getPhaseSucceeded()
	{
		if ($this->result == self::COMPLETED) {
			return true;
		}

		return false;
	}

	public function getPhaseFailed()
	{
		if ($this->result == self::FAILED) {
			return true;
		}

		return false;
	}

	public function getPhaseIsIncomplete()
	{
		if ($this->result == self::INCOMPLETE) {
			return true;
		}

		return false;
	}

	public function getPhaseHasNoActions()
	{
		if ($this->result == self::HASNOACTIONS) {
			return true;
		}

		return false;
	}

	public function getPhaseIsBlacklisted()
	{
		if ($this->result == self::BLACKLISTED) {
			return true;
		}

		return false;
	}

	public function getPhaseWasSkipped()
	{
		if ($this->result == self::SKIPPED) {
			return true;
		}

		return false;
	}

	public function getNextAction()
	{
		return $this->nextAction;
	}

	public function setContinuePlaying($result = 1, $msg = null)
	{
		$this->nextAction = PhasesPlayer::NEXT_CONTINUE;
		$this->result     = $result;
		$this->message    = $msg;
	}

	public function setPlayingFailed($result, $msg)
	{
		$this->nextAction = PhasesPlayer::NEXT_FAIL;
		$this->result     = $result;
		$this->message    = $msg;
	}

	public function setSkipPhases($result, $msg)
	{
		$this->nextAction = PhasesPlayer::NEXT_SKIP;
		$this->result     = $result;
		$this->message    = $msg;
	}

	public function addPairedPhase($phaseName)
	{
		$this->pairedPhases[$phaseName] = $phaseName;
	}

	public function getPairedPhases()
	{
		return $this->pairedPhases;
	}

	public function hasPairedPhases()
	{
		if (count($this->pairedPhases) > 0) {
			return true;
		}

		return false;
	}
}