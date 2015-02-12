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
use Prose\E5xx_ActionFailed;
use Prose\E5xx_ExpectFailed;
use Prose\E5xx_NotImplemented;

/**
 * the Automate phase, for scripts
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class AutomatePhase extends StoryPhase
{
	public function doPhase($script)
	{
		// shorthand
		$st           = $this->st;
		$scriptResult = $script->getResult();

		// keep track of what happens with the action
		$phaseResult = $this->getNewPhaseResult();

		// run ONE of the actions, picked at random
		try {
			// run the script
			include $script->getFilename();

			// if we get here, all is well
			$phaseResult->setContinuePlaying();
			$scriptResult->setPhaseGroupHasSucceeded();
		}

		// if the set of actions fails, it will throw this exception
		catch (E5xx_ActionFailed $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$scriptResult->setPhaseGroupHasFailed($phaseResult);
		}
		catch (E5xx_ExpectFailed $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::FAILED,
				$e->getMessage(),
				$e
			);
			$scriptResult->setPhaseGroupHasFailed($phaseResult);
		}

		// we treat this as a hard failure
		catch (E5xx_NotImplemented $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::INCOMPLETE,
				$e->getMessage(),
				$e
			);
			$scriptResult->setPhaseGroupIsIncomplete($phaseResult);
		}

		// if this happens, something has gone badly wrong
		catch (Exception $e) {
			$phaseResult->setPlayingFailed(
				$phaseResult::ERROR,
				$e->getMessage(),
				$e
			);
			$scriptResult->setPhaseGroupHasError($phaseResult);
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// all done
		return $phaseResult;
	}
}