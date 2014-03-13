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
use stdClass;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\ObjectLib\E5xx_NoSuchProperty;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\E5xx_ExpectFailed;
use DataSift\Storyplayer\Prose\E5xx_NotImplemented;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\UserLib\UserGenerator;

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
class StoryPlayer
{
	public function play(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story   = $st->getStory();
		$env     = $st->getEnvironment();
		$envName = $st->getEnvironmentName();
		$output  = $st->getOutput();

		// set default callbacks up
		$story->setDefaultCallbacks();

		// keep track of how each phase goes
		$result = new StoryResult($story);

		// tell the outside world what we're doing
		$this->announceStory($st);

		// is this story allowed to run on the current environment?
		$blacklistedEnvironment = false;
		if (isset($env->mustBeWhitelisted) && $env->mustBeWhitelisted) {
			// by default, stories are not allowed to run on this environment
			$blacklistedEnvironment = true;

			// is this story allowed to run?
			$whitelistedEnvironments = $story->getWhitelistedEnvironments();
			if (isset($whitelistedEnvironments[$envName]) && $whitelistedEnvironments[$envName]) {
				$blacklistedEnvironment = false;
			}
		}

		// are we allowed to proceed?
		if ($blacklistedEnvironment) {
			// no, we are not
			//
			// tell the user what happened
			Log::write(Log::LOG_NOTICE, "Cannot run story against the environment '{$envName}'");

			// all done
			return $result;
		}

		// if we get here, then the story is allowed to run against
		// the current environment
		$result->storyAttempted = true;

		// execute each phase
		foreach (StoryPhases::$phasesToClasses as $phaseNumber => $phaseClass) {
			// what is the full name of the class for this phase?
			$class  = 'DataSift\StoryPlayer\PlayerLib\\' . $phaseClass;
			$phase  = new $class($st, $phaseNumber);

			$result->addPhaseResult($phase->runPhase($st));
		}

		// setup the test environment
		$setupEnvironmentResult = $result->addPhaseResult(
			StoryPhases::PHASE_TESTENVIRONMENTSETUP,
			$this->doTestEnvironmentSetup($st, $staticConfig)
		);

		// setup the test itself ... but only if we have a valid
		// test environment
		if ($setupEnvironmentResult == StoryResults::SETUP_SUCCESS) {
			$setupTestResult = $result->addPhaseResult(
				StoryPhases::PHASE_TESTSETUP,
				$this->doTestSetup($st, $staticConfig)
			);
		}

		// work out if this story should pass or fail
		if ($setupEnvironmentResult == StoryResults::SETUP_SUCCESS && $setupTestResult == StoryResults::SETUP_SUCCESS) {
			$actionShouldWork = $result->addPhaseResult(
				StoryPhases::PHASE_PRETESTPREDICTION,
				$this->doPreTestPrediction($st, $staticConfig)
			);

			// capture any data before we run the test
			$this->doPreTestInspection($st, $staticConfig);

			// keep track of what happens with the action
			$actionResult = $result->addPhaseResult(
				StoryPhases::PHASE_ACTION,
				$this->doOneAction($st, $staticConfig)
			);

			// are we happy with the test results?
			$actionWorked = $result->addPhaseResult(
				StoryPhases::PHASE_POSTTESTINSPECTION,
				$this->doPostTestInspection($st, $staticConfig)
			);
		}
		else {
			// as the setup steps failed, we do not know what the
			// outcome of the other setups would have been
			$actionShouldWork = StoryResults::$defaultPhaseOutcomes[StoryPhases::PHASE_PRETESTPREDICTION];
			$actionResult     = StoryResults::$defaultPhaseOutcomes[StoryPhases::PHASE_ACTION];
			$actionWorked     = StoryResults::$defaultPhaseOutcomes[StoryPhases::PHASE_POSTTESTINSPECTION];
		}

		// tidy up the test, if we had a working test environment
		// to attempt to set the test up within
		if ($setupEnvironmentResult == StoryResults::SETUP_SUCCESS) {
			$result->addPhaseResult(
				StoryPhases::PHASE_TESTTEARDOWN,
				$this->doTestTeardown($st, $staticConfig)
			);
		}

		// tidy up the environment, regardless of whether setting it up
		// failed or not
		$result->addPhaseResult(
			StoryPhases::PHASE_TESTENVIRONMENTTEARDOWN,
			$this->doTestEnvironmentTeardown($st, $staticConfig)
		);

		// stop the test device, if it is still running
		$st->stopDevice();

		// if the action completed successfully, then the user may have
		// changed state ... let's deal with that
		if ($actionResult == StoryResults::ACTION_COMPLETED && $actionWorked == StoryResults::INSPECT_SUCCESS) {
			$this->announcePhase($st, 8, 'Role Changes');
			$this->applyRoleChanges($st, $staticConfig);
		}

		// calculate the final result
		$finalResults = [
			'prediction' => $actionShouldWork,
			'action'     => $actionResult,
			'validation' => $actionWorked
		];
		$result->calculateStoryResult($finalResults);

		// announce the results
		$this->announcePhase($st, 9, 'Final Results');
		$output->endStory($result->storyResult);

		// all done
		return $result;
	}

	// ====================================================================
	//
	// TEST ENVIRONMENT SETUP / TEAR-DOWN SUPPORT
	//
	// --------------------------------------------------------------------

	public function doTestEnvironmentSetup(StoryTeller $st, stdClass $staticConfig)
	{
		// our return value
		$return = StoryResults::SETUP_SUCCESS;

		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announcePhase($st, 1, 'Setup test environment');

		// do we have anything to do?
		if (!$story->hasTestEnvironmentSetup())
		{
			Log::write(Log::LOG_INFO, "story has no test environment setup instructions");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return $return;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('TestEnvironmentSetup', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test environment setup is disabled; skipping");

			// as far as the rest of the test is concerned, the setup
			// was a success
			return $return;
		}

		// get the callbacks to call
		$callbacks = $story->getTestEnvironmentSetup();

		// make the call
		try {
			$st->setCurrentPhase(StoryPhases::PHASE_TESTENVIRONMENTSETUP);
			foreach ($callbacks as $callback){
				call_user_func($callback, $st);
			}
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform test environment setup; " . (string)$e . "\n" . $e->getTraceAsString());
			$return = StoryResults::SETUP_FAIL;
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// all done
		return $return;
	}

	public function doTestEnvironmentTeardown(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announcePhase($st, 8, 'Teardown test environment');

		// do we have anything to do?
		if (!$story->hasTestEnvironmentTeardown())
		{
			Log::write(Log::LOG_INFO, "story has no test environment teardown instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('TestEnvironmentTeardown', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test environment teardown is disabled; skipping");
			return;
		}

		// get the callback to call
		$callbacks = $story->getTestEnvironmentTeardown();

		// make the call
		try {
			$st->setCurrentPhase(StoryPhases::PHASE_TESTENVIRONMENTTEARDOWN);
			foreach ($callbacks as $callback){
				call_user_func($callback, $st);
			}
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to complete test environment teardown; " . (string)$e . "\n" . $e->getTraceAsString());
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// all done
	}

	// ====================================================================
	//
	// STORY SETUP / TEAR-DOWN SUPPORT
	//
	// --------------------------------------------------------------------

	public function doTestSetup(StoryTeller $st, stdClass $staticConfig)
	{
		// our return value
		$return = StoryResults::SETUP_SUCCESS;

		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announcePhase($st, 2, 'Setup story');

		// do we have anything to do?
		if (!$story->hasTestSetup())
		{
			Log::write(Log::LOG_INFO, "story has no test setup instructions");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return $return;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('TestSetup', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test setup is disabled; skipping");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return;
		}

		// setup the phase
		$st->setCurrentPhase(StoryPhases::PHASE_TESTSETUP);
		$this->doPerPhaseSetup($st);

		// get the callback to call
		$callbacks = $story->getTestSetup();

		// make the call
		try {
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}
		}
		catch (Exception $e)
		{
			Log::write(Log::LOG_CRITICAL, "unable to perform test setup; " . (string)$e . "\n" . $e->getTraceAsString());
			$return = StoryResults::SETUP_FAIL;
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
		return $return;
	}

	public function doTestTeardown(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announcePhase($st, 7, 'Teardown story');

		// do we have anything to do?
		if (!$story->hasTestTeardown())
		{
			Log::write(Log::LOG_INFO, "story has no test teardown instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('TestTeardown', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test teardown is disabled; skipping");
			return;
		}

		// get the callback to call
		$callbacks = $story->getTestTeardown();

		// make the call
		try {
			$st->setCurrentPhase(StoryPhases::PHASE_TESTTEARDOWN);
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}
		}
		catch (Exception $e)
		{
			Log::write(Log::LOG_CRITICAL, "unable to perform test teardown; " . (string)$e . "\n" . $e->getTraceAsString());
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
	}

	// ====================================================================
	//
	// PER-PHASE SETUP / TEAR-DOWN ACTIONS
	//
	// --------------------------------------------------------------------

	public function doPerPhaseSetup(StoryTeller $st)
	{
		// shorthand
		$story = $st->getStory();

		// do we have anything to do?
		if (!$story->hasPerPhaseSetup())
		{
			return;
		}

		// get the callback to call
		$callbacks = $story->getPerPhaseSetup();

		// make the call
		foreach ($callbacks as $callback) {
			call_user_func($callback, $st);
		}

		// all done
	}

	public function doPerPhaseTeardown(StoryTeller $st)
	{
		// shorthand
		$story = $st->getStory();

		// do we have anything to do?
		if ($story->hasPerPhaseTeardown())
		{
			// get the callback to call
			$callbacks = $story->getPerPhaseTeardown();

			// make the call
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}
		}

		// stop the test device, if it is still running
		$st->stopDevice();

		// all done
	}

	// ====================================================================
	//
	// PRE-TEST INSPECTION
	//
	// --------------------------------------------------------------------

	public function doPreTestPrediction(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// our default return value
		$actionShouldWork = StoryResults::PREDICT_SUCCESS;

		try {
			$this->announcePhase($st, 3, 'Pre-test prediction');

			// do we have anything to do?
			if (!$story->hasPreTestPrediction())
			{
				Log::write(Log::LOG_INFO, "story has no pre-test prediction instructions");
				Log::write(Log::LOG_INFO, "assuming that the action should always succeed");
				return $actionShouldWork;
			}

			// should we do this stage?
			if (!$this->shouldExecutePhase('PreTestPrediction', $staticConfig)) {
				Log::write(Log::LOG_INFO, "pre-test prediction is disabled; skipping");
				Log::write(Log::LOG_INFO, "assuming that the action should always succeed");
				return $actionShouldWork;
			}

			// setup the phase
			$st->setCurrentPhase(StoryPhases::PHASE_PRETESTPREDICTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$story = $st->getStory();
			$callbacks = $story->getPreTestPrediction();

			foreach ($callbacks as $callback) {
				if (is_callable($callback)) {
					call_user_func($callback, $st);
				}
			}
		}
		// in any of the expects() calls in the preflight checks fails,
		// an E5xx_ActionFailed will be thrown
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "pre-test prediction failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = StoryResults::PREDICT_FAIL;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "pre-test prediction failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = StoryResults::PREDICT_FAIL;
		}
		// if any of the tests are incomplete, deal with that too
		catch (E5xx_NotImplemented $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform pre-test prediction; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = StoryResults::PREDICT_INCOMPLETE;
		}
		// deal with the things that go wrong
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform pre-test prediction; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = StoryResults::PREDICT_UNKNOWN;
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
		return $actionShouldWork;
	}

	// ====================================================================
	//
	// PRE-TEST INSPECTION
	//
	// --------------------------------------------------------------------

	public function doPreTestInspection(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announcePhase($st, 4, 'Pre-test inspection');

		// do we have anything to do?
		if (!$story->hasPreTestInspection())
		{
			Log::write(Log::LOG_INFO, "story has no pre-test inspection instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('PreTestInspection', $staticConfig)) {
			// we assume that the absence means that we just don't know
			Log::write(Log::LOG_INFO, "pre-test inspection is disabled; skipping");
			return;
		}

		// this could all go horribly wrong ... so wrap it up and deal
		// with it if it explodes
		try {
			// do any required setup
			$st->setCurrentPhase(StoryPhases::PHASE_PRETESTINSPECTION);
			$this->doPerPhaseSetup($st);

			// if the callback exists, use it
			$story = $st->getStory();
			$callbacks = $story->getPreTestInspection();
			foreach ($callbacks as $callback) {
				call_user_func($callback, $st);
			}
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform pre-test inspection; " . (string)$e . "\n" . $e->getTraceAsString());
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
	}

	// ====================================================================
	//
	// TEST ACTION
	//
	// --------------------------------------------------------------------

	public function doOneAction(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// keep track of what happens with the action
		$actionResult = StoryResults::ACTION_COMPLETED;

		// tell the user what we are doing
		$this->announcePhase($st, 5, 'Action');

		// do we have anything to do?
		if (!$story->hasActions())
		{
			Log::write(Log::LOG_INFO, "story has no action instructions");
			return StoryResults::ACTION_HASNOACTIONS;
		}

		// should we do this stage?
		if (!$this->shouldExecutePhase('PreTestInspection', $staticConfig)) {
			Log::write(Log::LOG_INFO, "actions are disabled; skipping");
			return StoryResults::ACTION_HASNOACTIONS;
		}

		// run ONE of the actions, picked at random
		try {

			// do any setup
			$st->setCurrentPhase(StoryResults::PHASE_ACTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$action = $story->getOneAction();
			$action($st);
		}

		// if the set of actions fails, it will throw this exception
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "action failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionResult = StoryResults::ACTION_FAILED;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "action failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionResult = StoryResults::ACTION_FAILED;
		}
		// deal with the things that go wrong ... but do NOT bail out,
		// because we need to run the postflight checks no matter what!
		catch (E5xx_NotImplemented $e) {
			// log what happened
			Log::write(Log::LOG_CRITICAL, "unable to complete actions; " . (string)$e . "\n" . $e->getTraceAsString());

			// mark this story as incomplete
			$actionResult = StoryResults::ACTION_INCOMPLETE;
		}
		catch (Exception $e) {
			// log what happened
			Log::write(Log::LOG_CRITICAL, "unable to complete actions; " . (string)$e . "\n" . $e->getTraceAsString());

			// mark this story as failed
			$actionResult = StoryResults::ACTION_FAILED;
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
		return $actionResult;
	}

	// ====================================================================
	//
	// REPORT TEST RESULT
	//
	// --------------------------------------------------------------------

	public function doPostTestInspection(StoryTeller $st, stdClass $staticConfig)
	{
		// shorthand
		$story = $st->getStory();

		// we don't care whether the action did not complete ... we want
		// to know whether the story thinks the actions that DID complete
		// were successful
		$actionWorked = StoryResults::INSPECT_SUCCESS;

		try {
			$this->announcePhase($st, 6, 'Post-test inspection');

			// do we have anything to do?
			if (!$story->hasPostTestInspection())
			{
				Log::write(Log::LOG_INFO, "story has no post-test inspection instructions");
				Log::write(Log::LOG_WARNING, "assuming that the action was successful (dangerous!)");
				return $actionWorked;
			}

			// should we do this stage?
			if (!$this->shouldExecutePhase('PostTestInspection', $staticConfig)) {
				Log::write(Log::LOG_INFO, "post-test inspection is disabled; skipping");
				Log::write(Log::LOG_WARNING, "assuming that the action was successful (dangerous!)");
				return $actionWorked;
			}

			// do any necessary setup
			$st->setCurrentPhase(StoryResults::PHASE_POSTTESTINSPECTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$story = $st->getStory();
			$callbacks = $story->getPostTestInspection();
			foreach ($callbacks as $callback) {
				if (is_callable($callback)) {
					call_user_func($callback, $st);
				}
			}
		}
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = StoryResults::INSPECT_FAIL;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = StoryResults::INSPECT_FAIL;
		}
		catch (E5xx_NotImplemented $e) {
			Log::write(Log::LOG_CRITICAL, "unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = StoryResults::INSPECT_INCOMPLETE;
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = StoryResults::INSPECT_UNKNOWN;
		}

		// close off any open log actions
		$st->closeAllOpenActions();

		// tidy up after ourselves
		$this->doPerPhaseTeardown($st);

		// all done
		return $actionWorked;
	}

	public function applyRoleChanges(StoryTeller $st, stdClass $staticConfig)
	{
		// which story is being told?
		$story = $st->getStory();

		if (!$story->hasRoleChanges()) {
			// nothing to see ... move along, move along
			Log::write(Log::LOG_DEBUG, 'Story has no role changes to apply');
			return;
		}

		Log::write(Log::LOG_DEBUG, 'Applying role changes to context');
		$callbacks = $story->getRoleChanges();

		// This used to return $callback($st)
		// @TODO Work out where it's passed to and if we need to continue returning it
		foreach ($callbacks as $callback) {
			call_user_func($callback, $st);
		}
	}

	public function announceStory(StoryTeller $st)
	{
		// shorthand
		$story = $st->getStory();
		$output = $st->getOutput();

		// tell all of our output plugins that the story has begun
		$output->startStory(
			$story->getName(),
			$story->getCategory(),
			$story->getGroup(),
			$st->getEnvironmentName(),
			$st->getDeviceName()
		);
	}
}
