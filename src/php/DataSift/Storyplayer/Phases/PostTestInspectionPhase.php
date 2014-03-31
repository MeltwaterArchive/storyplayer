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

use Exception;
use DataSift\StoryPlayer\Prose\E5xx_ActionFailed;
use DataSift\StoryPlayer\Prose\E5xx_ExpectFailed;
use DataSift\StoryPlayer\Prose\E5xx_NotImplemented;

/**
 * the PostTestInspection phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class PostTestInspectionPhase extends StoryPhase
{
	public function doPhase()
	{
		// shorthand
		$st          = $this->st;
		$story       = $st->getStory();
		$storyResult = $st->getStoryResult();

		// our results object
		$phaseResult = new PhaseResult;

		try {
			// do we have anything to do?
			if (!$story->hasPostTestInspection())
			{
				$phaseResult->setContinuePlaying(
					PhaseResult::HASNOACTIONS,
					"story has no post-test inspection instructions"
				);
				return $phaseResult;
			}

			// do any necessary setup
			$this->doPerPhaseSetup();

			// make the call
			$story = $st->getStory();
			$callbacks = $story->getPostTestInspection();
			foreach ($callbacks as $callback) {
				if (is_callable($callback)) {
					call_user_func($callback, $st);
				}
			}

			// if we get here, the post-test inspection did not fail
			// ... but should it have?
			if ($storyResult->getStoryShouldFail()) {
				$storyResult->setStoryHasFailed();
				$phaseResult->setPlayingFailed(
					PhaseResult::SUCCESS,
					"post-test inspection succeeded when it was expected to fail"
				);
			}
			else {
				$phaseResult->setContinuePlaying();
			}
		}
		catch (E5xx_ActionFailed $e) {
			$msg = "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString();
			if ($storyResult->getStoryShouldFail()) {
				$phaseResult->setContinuePlaying(
					PhaseResult::SUCCESS,
					$msg
				);
			}
			else {
				$storyResult->setStoryHasFailed();
				$phaseResult->setPlayingFailed(
					PhaseResult::FAILED,
					$msg
				);
			}
		}
		catch (E5xx_ExpectFailed $e) {
			$msg = "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString();
			if ($storyResult->getStoryShouldFail()) {
				$phaseResult->setContinuePlaying(
					PhaseResult::SUCCESS,
					$msg
				);
			}
			else {
				$storyResult->setStoryHasFailed();
				$phaseResult->setPlayingFailed(
					PhaseResult::FAILED,
					$msg
				);
			}
		}

		// this is treated as a hard fail
		catch (E5xx_NotImplemented $e) {
			$storyResult->setStoryHasFailed();
			$phaseResult->setPlayingFailed(
				PhaseResult::INCOMPLETE,
				"unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString()
			);
		}
		// this only happens when something has gone very badly wrong
		catch (Exception $e) {
			$storyResult->setStoryHasFailed();
			$phaseResult->setPlayingFailed(
				PhaseResult::INCOMPLETE,
				"unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString()
			);
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown();

		// all done
		return $phaseResult;
	}
}