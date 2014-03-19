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

namespace DataSift\Storyplayer\PlayerLib;

use Exception;
use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\StoryLib\Story;

/**
 * a record of what happened with a story
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryResult
{
	public $story = null;
	public $phases = array();
	public $storyResult = NULL;
	public $storyAttempted = false;
	public $storyShouldFail = false;

	const PASS        = 1;
	const FAIL        = 2;
	const UNKNOWN     = 3;
	const INCOMPLETE  = 4;
	const BLACKLISTED = 5;

	public function __construct(Story $story)
	{
		// remember the story we are reporting on
		$this->story = $story;
	}

	public function addPhaseResult($phase, $phaseResult)
	{
		$phaseName = $phase->getPhaseName();
		$this->phases[$phaseName] = $phaseResult;
	}

	public function getPhaseResult($phaseName)
	{
		// do we have the data?
		if (isset($this->phases[$phaseName])) {
			return $this->phases[$phaseName];
		}

		// if we get here, we have no data
		throw new E5xx_NoResultForPhase($phaseName);
	}

	public function getPhaseOutcome($phaseName)
	{
		// do we have the data?
		if (isset($this->phases[$phaseName])) {
			return $this->phases[$phaseName]->outcome;
		}

		// if we get here, we have no data
		throw new E5xx_NoResultForPhase($phaseName);
	}

	public function getStoryShouldFail()
	{
		return $this->storyShouldFail;
	}

	public function setStoryShouldFail()
	{
		$this->storyShouldFail = true;
	}

	public function setStoryHasBeenBlacklisted()
	{
		$this->storyResult = self::BLACKLISTED;
	}

	public function setStoryIsIncomplete()
	{
		$this->storyResult = self::SKIPPED;
	}

	public function setStoryHasFailed()
	{
		$this->storyResult = self::FAIL;
	}

	public function calculateStoryResult()
	{
		return;
		// shorthand
		$actionShouldWork = $this->getPhaseOutcome(StoryPhases::PHASE_PRETESTPREDICTION);
		$actionResult     = $this->getPhaseOutcome(StoryPhases::PHASE_ACTION);
		$actionWorked	  = $this->getPhaseOutcome(StoryPhases::PHASE_POSTTESTINSPECTION);

		if ($actionShouldWork == StoryResults::PREDICT_SUCCESS && ($actionResult == StoryResults::ACTION_COMPLETED || $actionResult == StoryResults::ACTION_HASNOACTIONS) && $actionWorked == StoryResults::INSPECT_SUCCESS) {
			$this->storyResult = StoryResults::RESULT_PASS;
		}
		else if ($actionShouldWork == StoryResults::PREDICT_FAIL && ($actionResult == StoryResults::ACTION_FAILED || $actionResult == StoryResults::ACTION_HASNOACTIONS) && $actionWorked == StoryResults::INSPECT_FAIL) {
			$this->storyResult = StoryResults::RESULT_PASS;
		}
		else if ($actionShouldWork == StoryResults::PREDICT_UNKNOWN || $actionShouldWork == StoryResults::PREDICT_INCOMPLETE) {
			$this->storyResult = StoryResults::RESULT_UNKNOWN;
		}
		else if ($actionResult == StoryResults::ACTION_INCOMPLETE || $actionResult == StoryResults::ACTION_UNKNOWN) {
			$this->storyResult = StoryResults::RESULT_UNKNOWN;
		}
		else if ($actionWorked == StoryResults::INSPECT_UNKNOWN || $actionWorked == StoryResults::INSPECT_INCOMPLETE) {
			$this->storyResult = StoryResults::RESULT_UNKNOWN;
		}
		else {
			$this->storyResult = StoryResults::RESULT_FAIL;
		}
	}
}
