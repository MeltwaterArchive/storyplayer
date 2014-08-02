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
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Phase_Result;

/**
 * base class for all phases
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

abstract class Phase
{
	const INTERNALPRE_PHASE    = 1;
	const STORY_PHASE          = 2;
	const STORY_SUPPORT_PHASE  = 3;
	const INTERNALPOST_PHASE   = 4;
	const INFRASTRUCTURE_PHASE = 5;

	protected $st;

	public function __construct(StoryTeller $st)
	{
		$this->st = $st;
	}

	public function announcePhaseStart()
	{
		// shorthand
		$output = $this->st->getOutput();

		// what is our name?
		$phaseName = $this->getPhaseName();

		// what kind of phase are we?
		$phaseType = $this->getPhaseType();

		// tell the world who we are
		$output->startPhase($phaseName, $phaseType);
	}

	public function announcePhaseEnd()
	{
		// shorthand
		$output = $this->st->getOutput();

		// what is our name?
		$phaseName = $this->getPhaseName();

		// what kind of phase are we?
		$phaseType = $this->getPhaseType();

		// all done
		$output->endPhase($phaseName, $phaseType);
	}

	public function getPhaseName()
	{
		static $phaseName = null;

		if (!isset($phaseName)) {
			$parts = explode('\\', get_class($this));
			$phaseName = str_replace('Phase', '', end($parts));
		}

		return $phaseName;
	}

	public function getNewPhaseResult()
	{
		return new Phase_Result($this->getPhaseName());
	}

	public function doPerPhaseSetup()
	{
		// shorthand
		$st    = $this->st;
		$story = $st->getStory();

		// do we have anything to do?
		if (!$story->hasPerPhaseSetup())
		{
			return;
		}

		// get the callback to call
		$callbacks = $story->getPerPhaseSetup();

		// make the call
		foreach ($callbacks as $callback) {
			call_user_func($callback, $st);
		}

		// all done
	}

	public function doPerPhaseTeardown()
	{
		// shorthand
		$st    = $this->st;
		$story = $st->getStory();

		// do we have anything to do?
		if ($story->hasPerPhaseTeardown())
		{
			// get the callback to call
			$callbacks = $story->getPerPhaseTeardown();

			// make the call
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}
		}

		// stop the test device, if it is still running
		if (!$st->getPersistDevice()) {
			$st->stopDevice();
		}

		// all done
	}

	abstract public function getPhaseType();
	abstract public function doPhase();
}