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
class Story_Result extends PhaseGroup_Result
{
	/**
	 *
	 * @var Story
	 */
	public $story           = null;

	/**
	 * is this a story where a failure is the expected outcome?
	 * @var boolean
	 */
	public $storyShouldFail = false;

	public $resultStrings = [
		'UNKNOWN',
		'PASS',
		'FAIL',
		'ERROR',
		'INCOMPLETE',
		'BLACKLISTED'
	];

	const PASS = 1;

	public function __construct(Story $story)
	{
		// initialise our parent first
		parent::__construct();

		// remember the story we are reporting on
		$this->story = $story;

		// we want success to say 'PASS' rather than 'OKAY'
		$this->resultStrings[self::OKAY] = 'PASS';
	}

	public function getStoryShouldFail()
	{
		return $this->storyShouldFail;
	}

	public function setStoryShouldFail()
	{
		$this->storyShouldFail = true;
	}

	public function setStoryHasSucceeded()
	{
		$this->setPhaseGroupHasSucceeded();
	}

	public function setStoryHasBeenBlacklisted($phaseResult)
	{
		$this->setPhaseGroupHasBeenBlacklisted($phaseResult);
	}

	public function setStoryIsIncomplete($phaseResult)
	{
		$this->setPhaseGroupIsIncomplete($phaseResult);
	}

	public function setStoryHasFailed($phaseResult)
	{
		$this->setPhaseGroupHasFailed($phaseResult);
	}

	public function setStoryHasError($phaseResult)
	{
		$this->setPhaseGroupHasError($phaseResult);
	}

	public function calculateStoryResult()
	{
		// no-op
	}
}
