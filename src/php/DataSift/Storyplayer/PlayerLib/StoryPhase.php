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

use DataSift\Storyplayer\StoryLib\Story;

/**
 * base class for all story phases
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class StoryPhase
{
	protected $st;
	protected $phaseNumber;

	public function __construct(Storyplayer $st, $phaseNumber)
	{
		$this->phaseNumber = $phaseNumber;
	}

	public function announcePhase(StoryTeller $st)
	{
		// shorthand
		$output = $st->getOutput();

		// what is our name?
		$phaseName = $this->getPhaseText();

		// tell the world who we are
		$output->startStoryPhase($phaseNumber, $phaseName);
	}

	public function shouldExecutePhase(stdClass $staticConfig)
	{
		// what is our name?
		$phaseName = $this->getPhaseName();

		// is this phase disabled in the config?
		if (!isset($staticConfig->phases, $staticConfig->phases->$phaseName) || !$staticConfig->phases->$phaseName)
		{
			return false;
		}

		return true;
	}

	public function getPhaseName()
	{
		// what is our name?
		$phaseName = StoryPhases::$phaseToName[$this->phaseNumber];

		// all done
		return $phaseName;
	}

	public function getPhaseText()
	{
		// what is our human-readable name?
		$phaseText = StoryPhases::$phaseToText[$this->phaseNumber];

		// all done
		return $phaseText;
	}
}