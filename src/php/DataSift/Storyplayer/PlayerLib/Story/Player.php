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

use DataSift\Storyplayer\Injectables;

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
		$this->startupPhases  = $injectables->activeConfig->getArray('storyplayer.phases.beforeStory');
		$this->storyPhases    = $injectables->activeConfig->getArray('storyplayer.phases.story');
		$this->shutdownPhases = $injectables->activeConfig->getArray('storyplayer.phases.afterStory');
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

        // does our story want to keep the test device open between
        // phases?
        if ($story->getPersistDevice()) {
        	$st->setPersistDevice();
        }

        // initialise the user
        //$context = $st->getStoryContext();
        //$context->initUser($st);

		// set default callbacks up
		$story->setDefaultCallbacks();

		// tell the outside world what we're doing
		$activity = "Running story";
		$name     = $story->getCategory() . ' > ' . $story->getGroup() . ' > ' . $story->getName();
		$output->startPhaseGroup($activity, $name);

		// run the phases before the story truly starts
		$phasesPlayer->playPhases(
			$activity,
			$st,
			$injectables,
			$this->startupPhases,
			$story
		);

		// what happened?
		$result = $story->getResult();
		if (!$result->getPhaseGroupSucceeded()) {
			// make sure the result has the story's filename in
			$result->filename = $this->storyFilename;

			// announce the results
			$output->endPhaseGroup($result);

			// all done
			return;
		}

		// run the phases in the 'story' section
		$phasesPlayer->playPhases(
			$activity,
			$st,
			$injectables,
			$this->storyPhases,
			$story
		);

		// grab the result at this point
		$result = clone $story->getResult();

		// run the shutdown phase
        $phasesPlayer->playPhases(
        	$activity,
			$st,
			$injectables,
			$this->shutdownPhases,
			$story
        );

        // do we also need to look at any failures that happened during
        // the shutdown phase?
        if ($result->getPhaseGroupSucceeded()) {
        	$result = $story->getResult();
        }

		// make sure the result has the story's filename in
		$result->filename = $this->storyFilename;

		// announce the results
		$output->endPhaseGroup($result);

		// all done
	}
}
