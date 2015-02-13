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
use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\Phases\Phase;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;
use Phix_Project\ExceptionsLib1\Legacy_ErrorException;

/**
 * runs a set of phases, and returns the result
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class PhaseGroup_Player
{
	const NEXT_CONTINUE = 1;
	const NEXT_SKIP     = 2;
	const NEXT_FAIL     = 3;

	const MSG_PHASE_BLACKLISTED = 'phase is not allowed to run';
	const MSG_PHASE_FAILED      = 'phase failed with an unexpected error';
	const MSG_PHASE_INCOMPLETE  = 'phase is incomplete';
	const MSG_PHASE_NOT_ACTIVE  = 'phase is marked as inactive';

	/**
	 * @param StoryTeller $st
	 * @param Injectables $injectables
	 * @param array $phases
	 */
	public function playPhases($activity, StoryTeller $st, Injectables $injectables, $phases, $thingBeingPlayed)
	{
		// shorthand
		$output  = $st->getOutput();

		// we are going to need something to help us load each of our
		// phases
		$phaseLoader = $injectables->phaseLoader;

		// pre-load all of the phases, before we execute them
		// this will trigger any PHP syntax errors now rather than
		// when we're part-way through executing our code
		$phasesToPlay = [];
		foreach ($phases as $phaseName => $isActive) {
			$phase = $phaseLoader->loadPhase($st, $phaseName);
			$phasesToPlay[$phaseName] = [
				'phase' => $phase,
				'isActive' => $isActive
			];
		}

		// the result of playing this group of phases
		$groupResult = null;
		if ($thingBeingPlayed){
			$groupResult = $thingBeingPlayed->getResult();
			$groupResult->setActivity($activity);
		}

        // we need to wrap our code to catch old-style PHP errors
        $legacyHandler = new Legacy_ErrorHandler();

		// execute each phase, until either:
		//
		// 1. all listed phases have been executed, or
		// 2. one of the phases says that the story has failed
		foreach ($phasesToPlay as $phaseName => $phaseData)
		{
			// shorthand
			$phase    = $phaseData['phase'];
			$isActive = $phaseData['isActive'];

			try {
				// tell the world that we're running this phase
				$output->startPhase($phase);

				// play the phase
				$phaseResult = $legacyHandler->run([$this, 'playPhase'], [$st, $injectables, $phase, $isActive, $thingBeingPlayed]);

				// remember the result of this phase
				//$phaseResults->addResult($phase, $phaseResult);

				// now, what do we do?
				$nextAction = $phaseResult->getNextAction();
				switch ($nextAction)
				{
					case self::NEXT_SKIP:
						// why?
						if ($phaseResult->getPhaseIsBlacklisted()) {
							if ($groupResult) {
								$groupResult->setPhaseGroupHasBeenBlacklisted($phaseResult);
							}
							$output->logPhaseSkipped($phaseName, self::MSG_PHASE_BLACKLISTED . ': ' . $phaseResult->getMessage());
						}
						else if ($phaseResult->getPhaseCannotRun()) {
							$output->logPhaseSkipped($phaseName, $phaseResult->getMessage());
						}
						else {
							if ($groupResult) {
								$groupResult->setPhaseGroupIsIncomplete($phaseResult);
							}
							$output->logPhaseSkipped($phaseName, self::MSG_PHASE_INCOMPLETE);
						}

						// tell the output plugins that this phase is over
						$output->endPhase($phase, $phaseResult);
						return;

					case self::NEXT_FAIL:
						if ($groupResult) {
							$groupResult->setPhaseGroupHasFailed($phaseResult);
						}
						$output->logPhaseError($phaseName, self::MSG_PHASE_FAILED . ': ' . $phaseResult->getMessage());

						// tell the output plugins that this phase is over
						$output->endPhase($phase, $phaseResult);
						return;

					case self::NEXT_CONTINUE:
						if ($groupResult) {
							// keep the result up to date, in case this
							// is the last one
							if ($phaseResult->getPhaseHasBeenSkipped()) {
								$groupResult->setPhaseGroupHasBeenSkipped();
							}
							else {
								$groupResult->setPhaseGroupHasSucceeded();
							}
						}
						// tell the output plugins that this phase is over
						$output->endPhase($phase, $phaseResult);
				}
			}

			// our ultimate safety net
			//
			// ANY TIME this gets executed, the phase itself has not
			// done sufficient error handling of its own!!
			catch (Exception $e) {
				// tell our output plugins what happened
				$output->logPhaseError($phaseName, "uncaught exception: " . (string)$e->getMessage() . $e->getTraceAsString());

				// we need to create a dummy phase result for this
				$phaseResult = new Phase_Result($phaseName);
				$phaseResult->setPlayingFailed($phaseResult::ERROR, self::MSG_PHASE_FAILED, $e);

				// tell the world that this phase is over
				$output->endPhase($phase, $phaseResult);

				// this is a fatal exception
				if ($groupResult) {
					$groupResult->setPhaseGroupHasError($phaseResult);
				}

				// run no more phases
				return;
			}
		}

		// all done
		// if ($groupResult) {
		// 	$groupResult->setPhaseGroupHasSucceeded();
		// }
	}

	/**
	 *
	 * @param  StoryTeller $st
	 * @param  Injectables $injectables
	 * @param  Phase       $phase
	 * @param  boolean     $isActive
	 * @return \DataSift\Storyplayer\PlayerLib\Phase_Result
	 */
	public function playPhase(StoryTeller $st, Injectables $injectables, Phase $phase, $isActive, $thingBeingPlayed = null)
	{
		// shorthand
		$output    = $st->getOutput();
		$phaseName = $phase->getPhaseName();

		// run the phase if we're allowed to
		if ($isActive) {
			$st->setCurrentPhase($phase);
			$phaseResult = $phase->doPhase($thingBeingPlayed);
		}
		else {
			$phaseResult = new Phase_Result($phaseName);
			$phaseResult->setContinuePlaying($phaseResult::SKIPPED);
			$output->logPhaseSkipped($phaseName, self::MSG_PHASE_NOT_ACTIVE);
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// stop any running test devices
		if (!$st->getPersistDevice()) {
			$st->stopDevice();
		}

		// close off any log actions left open by closing down
		// the test device
		$st->closeAllOpenActions();

		// all done
		return $phaseResult;
	}
}
