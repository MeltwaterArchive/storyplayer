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

use DataSift\Storyplayer\Cli\Injectables;

/**
 * the main class for animating a single story
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Story_Player
{
	/**
	 * path to the story that we are going to play
	 *
	 * @var string
	 */
	protected $storyFilename;

	/**
	 * a list of the phases we need to run to get everything ready to
	 * run the actual story
	 *
	 * @var array
	 */
	protected $startupPhases;

	/**
	 * a list of the phases that make up the story
	 *
	 * @var array
	 */
	protected $storyPhases;

	/**
	 * a list of the phases that we need to run once the story has
	 * finished
	 *
	 * @var array
	 */
	protected $shutdownPhases;

	public function __construct($storyFilename, Injectables $injectables)
	{
		$this->storyFilename  = $storyFilename;
		$this->startupPhases  = $injectables->activeConfig->storyplayer->phases->startup;
		$this->storyPhases    = $injectables->activeConfig->storyplayer->phases->story;
		$this->shutdownPhases = $injectables->activeConfig->storyplayer->phases->shutdown;
	}

	public function play(StoryTeller $st, Injectables $injectables)
	{
		// shorthand
		$output = $st->getOutput();

        // we're going to use this to play our setup and teardown phases
        $phasesPlayer = new PhaseGroup_Player();

        // load our story
        $story = Story_Loader::loadStory($this->storyFilename);
        $st->setStory($story);

        // keep track of our results
        $storyResult = new Story_Result($story);

        // does our story want to keep the test device open between
        // phases?
        if ($story->getPersistDevice()) {
        	$st->setPersistDevice();
        }

        // initialise the user
        //$context = $st->getStoryContext();
        //$context->initUser($st);

        // run the startup phase
        $phasesPlayer->playPhases(
        	$st,
        	$injectables,
        	$this->startupPhases,
        	$storyResult
        );

		// set default callbacks up
		$story->setDefaultCallbacks();

		// tell the outside world what we're doing
		$output->startStory(
			$story->getName(),
			$story->getCategory(),
			$story->getGroup(),
			$st->getTestEnvironmentName(),
			$st->getDeviceName()
		);

		// run the phases in the 'story' section
		$phasesPlayer->playPhases(
			$st,
			$injectables,
			$this->storyPhases,
			$storyResult
		);

		// play the 'paired' phases too, in case they haven't yet
		// executed correctly
		$phasesPlayer->playPairedPhases(
			$st,
			$injectables,
			$this->storyPhases,
			$storyResult
		);

		// make sense of what happened
		$storyResult = $st->getStoryResult();
		$storyResult->calculateStoryResult($storyResult);

		// announce the results
		$output->endStory($storyResult);

		// run the shutdown phase
		//
		// we don't reuse the $storyResult here, because we don't want
		// the shutdown phase to affect the result we report back
		$phaseResults = new PhaseGroup_Result();
        $phasesPlayer->playPhases(
			$st,
			$injectables,
			$this->shutdownPhases,
			$phaseResults
        );

		// all done
		//var_dump($storyResult);
		return $storyResult;
	}
}
