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
 * the TestTeardown phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class TestTeardownPhase extends StoryPhase
{
	public function doPhase()
	{
		// shorthand
		$st    = $this->st;
		$story = $st->getStory();

		// our result
		$phaseResult = new PhaseResult;

		// do we have anything to do?
		if (!$story->hasTestTeardown($this->getPhaseName()))
		{
			$phaseResult->setContinuePlaying(
				PhaseResult::HASNOACTIONS,
				"story has no test teardown instructions"
			);
			return $phaseResult;
		}

		// get the callback to call
		$callbacks = $story->getTestTeardown();

		// make the call
		try {
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}

			// all is good
			$phaseResult->setContinuePlaying();
		}
		catch (E5xx_ActionFailed $e) {
			// we always continue at this point, even though the phase
			// itself failed
			$phaseResult->setContinuePlaying(
				PhaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryHasFailed($phaseResult);
		}
		catch (E5xx_ExpectFailed $e) {
			// we always continue at this point, even though the phase
			// itself failed
			$phaseResult->setContinuePlaying(
				PhaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryHasFailed($phaseResult);
		}
		catch (E5xx_NotImplemented $e) {
			// we always continue at this point, even though the phase
			// itself failed
			$phaseResult->setContinuePlaying(
				PhaseResult::INCOMPLETE,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryIsIncomplete($phaseResult);
		}
		catch (Exception $e)
		{
			// we still want to continue at this stage
			$phaseResult->setContinuePlaying(
				PhaseResult::ERROR,
				$e->getMessage(),
				$e
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