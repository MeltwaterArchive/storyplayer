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
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\E5xx_ExpectFailed;
use DataSift\Storyplayer\Prose\E5xx_NotImplemented;

/**
 * the PreTestPrediction phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class PreTestPredictionPhase extends StoryPhase
{
	public function doPhase($story)
	{
		// shorthand
		$st          = $this->st;
		$storyResult = $story->getStoryResult();

		// our return value
		$phaseResult = $this->getNewPhaseResult();

		try {
			// do we have anything to do?
			if (!$story->hasPreTestPrediction())
			{
				$phaseResult->setContinuePlaying(
					$phaseResult::HASNOACTIONS,
					"story has no pre-test prediction instructions; skipping"
				);
				return $phaseResult;
			}

			// setup the phase
			$this->doPerPhaseSetup();

			// make the call
			$callbacks = $story->getPreTestPrediction();

			foreach ($callbacks as $callback) {
				if (is_callable($callback)) {
					call_user_func($callback, $st);
				}
			}

			// if we get here, the PreTestPrediction worked with
			// no problems at all
			$phaseResult->setContinuePlaying();
		}
		// in any of the expects() calls in the preflight checks fails,
		// an E5xx_ActionFailed will be thrown
		catch (E5xx_ActionFailed $e) {
			$phaseResult->setContinuePlaying(
				$phaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryShouldFail();
		}
		catch (E5xx_ExpectFailed $e) {
			$phaseResult->setContinuePlaying(
				$phaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryShouldFail();
		}
		// if any of the tests are incomplete, deal with that too
		catch (E5xx_NotImplemented $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::INCOMPLETE,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryIsIncomplete($phaseResult);
		}
		// deal with the things that go wrong
		catch (Exception $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::ERROR,
				$e->getMessage(),
				$e
			);
			$storyResult->setStoryHasError($phaseResult);
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown();

		// all done
		return $phaseResult;
	}
}