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

	protected $pairedPhases = [];

	/**
	 * @param StoryTeller $st
	 * @param Injectables $injectables
	 * @param array $phases
	 */
	public function playPhases(StoryTeller $st, Injectables $injectables, $phases, $thingBeingPlayed)
	{
		// shorthand
		$output  = $st->getOutput();

		// keep track of any paired phases
		//
		// these are phases that need to run on cleanup because an
		// earlier phase was attempted
		$this->pairedPhases = [];

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
		}

		// execute each phase, until either:
		//
		// 1. all listed phases have been executed, or
		// 2. one of the phases says that the story has failed
		foreach ($phasesToPlay as $phaseName => $phaseData)
		{
			// shorthand
			$phase    = $phaseData['phase'];
			$isActive = $phaseData['isActive'];

			// remove this phase from the list of paired phases
			//
			// this ensures we do not accidentally execute a phase
			// twice or more!
			if (isset($this->pairedPhases[$phaseName])) {
				unset($this->pairedPhases[$phaseName]);
			}

			try {
				// tell the world that we're running this phase
				$phase->announcePhaseStart();

				// play the phase
				$phaseResult = $this->playPhase($st, $injectables, $phase, $isActive, $thingBeingPlayed);

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
								$groupResult->setPhaseGroupIsBlacklisted($phaseResult);
							}
							$output->logPhaseSkipped($phaseName, self::MSG_PHASE_BLACKLISTED . ': ' . $phaseResult->getMessage());
						}
						else {
							if ($groupResult) {
								$groupResult->setPhaseGroupIsIncomplete($phaseResult);
							}
							$output->logPhaseSkipped($phaseName, self::MSG_PHASE_INCOMPLETE);
						}

						// tell the output plugins that this phase is over
						$phase->announcePhaseEnd();
						return;

					case self::NEXT_FAIL:
						if ($groupResult) {
							$groupResult->setPhaseGroupHasFailed($phaseResult);
						}
						$output->logPhaseError($phaseName, self::MSG_PHASE_FAILED . ': ' . $phaseResult->getMessage());

						// tell the output plugins that this phase is over
						$phase->announcePhaseEnd();
						return;

					case self::NEXT_CONTINUE:
						// tell the output plugins that this phase is over
						$phase->announcePhaseEnd();
				}
			}

			// our ultimate safety net
			//
			// ANY TIME this gets executed, the phase itself has not
			// done sufficient error handling of its own!!
			catch (Exception $e) {
				// tell our output plugins what happened
				$output->logPhaseError($phaseName, "uncaught exception: " . (string)$e->getMessage());

				// we need to create a dummy phase result for this
				$phaseResult = new Phase_Result($phaseName);
				$phaseResult->setPlayingFailed($phaseResult::ERROR, self::MSG_PHASE_FAILED, $e);

				// tell the world that this phase is over
				$phase->announcePhaseEnd();

				// this is a fatal exception
				if ($groupResult) {
					$groupResult->setPhaseGroupHasError($phaseResult);
				}

				// run no more phases
				return;
			}
		}

		// all done
		if ($groupResult) {
			$groupResult->setPhaseGroupHasSucceeded();
		}
	}

	/**
	 * @param StoryTeller $st
	 * @param Injectables $injectables
	 * @param array $phases
	 * @param Phase_Results $phaseResults
	 */
	public function playPairedPhases(StoryTeller $st, Injectables $injectables, $phases, $thingBeingPlayed = null)
	{
		// special case
		//
		// do we actually have any work to do?
		if (!isset($this->pairedPhases)) {
			// nope - so let's bail now
			return;
		}

		// ----------------------------------------------------------------
		// CLEANUP TIME
		//
		// execute any paired phases that haven't yet been executed
		//
		// we execute all of the paired phases here, even if they fail,
		// because we want all of the cleaning up to at least be attempted
		//
		// this is (essentially) a slimmed down version of playPhases()

		// we are going to need something to help us load each of our
		// phases
		$phaseLoader = $injectables->phaseLoader;

		foreach ($this->pairedPhases as $phaseName)
		{
			// is the phase active?
			//
			// by default, we assume that it is
			$isActive = true;

			// check the phases list to see if it has a different
			// opinion
			if (isset($phases->$phaseName)) {
				$isActive = $phases->$phaseName;
			}

			// load the phase
			$phase = $phaseLoader->loadPhase($st, $phaseName);

			// tell the world that we're running this phase
			$phase->announcePhaseStart();

			// play the phase
			$phaseResult = $this->playPhase($st, $injectables, $phase, $isActive, $thingBeingPlayed);

			// tell the world that the phase is over
			$phase->announcePhaseEnd();

			// we *always* continue, even if the phase failed
		}

		// reset our list of paired phases, in case we get reused
		$this->pairedPhases = [];

		// all done
	}

	/**
	 *
	 * @param  StoryTeller $st
	 * @param  Injectables $injectables
	 * @param  Phase       $phase
	 * @param  boolean     $isActive
	 * @return PhaseResult
	 */
	protected function playPhase(StoryTeller $st, Injectables $injectables, Phase $phase, $isActive, $thingBeingPlayed = null)
	{
		// shorthand
		$output  = $st->getOutput();

		// run the phase if we're allowed to
		if ($isActive) {
			$st->setCurrentPhase($phase);
			$phaseResult = $phase->doPhase($thingBeingPlayed);
		}
		else {
			$phaseName = $phase->getPhaseName();
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
