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
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\E5xx_NotImplemented;
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
	const SETUP_SUCCESS      = 1;
	const SETUP_FAIL         = 2;

	const PREDICT_SUCCESS    = 1;
	const PREDICT_FAIL       = 2;
	const PREDICT_INCOMPLETE = 3;
	const PREDICT_UNKNOWN    = 4;

	const STORY_COMPLETED    = 1;
	const STORY_FAILED       = 2;
	const STORY_INCOMPLETE   = 3;
	const STORY_UNKNOWN      = 4;
	const STORY_HASNOACTIONS = 5;

	const INSPECT_SUCCESS    = 1;
	const INSPECT_FAIL       = 2;
	const INSPECT_INCOMPLETE = 3;
	const INSPECT_UNKNOWN    = 4;

	const RESULT_PASS = 1;
	const RESULT_FAIL = 2;
	const RESULT_UNKNOWN = 3;

	const PHASE_TESTENVIRONMENTSETUP = 1;
	const PHASE_TESTSETUP = 2;
	const PHASE_PRETESTINSPECTION = 3;
	const PHASE_PRETESTPREDICTION = 4;
	const PHASE_ACTION = 5;
	const PHASE_POSTTESTINSPECTION = 6;
	const PHASE_TESTTEARDOWN = 7;
	const PHASE_TESTENVIRONMENTTEARDOWN = 8;

	public function createContext(stdClass $staticConfig, stdClass $runtimeConfig, $envName, Story $story)
	{
		// create our context, which is just a container
		$context = new StoryContext();

		// we need to work out which environment we are running against,
		// as all other decisions are affected by this
		$context->env->mergeFrom($staticConfig->environments->defaults);
		$context->env->mergeFrom($staticConfig->environments->$envName);

		// we need to remember the name of the environment too!
		$context->env->envName = $envName;

		// put our runtime data in place too
		//
		// we don't force a copy of this data, because we're going to
		// write the changes out to disk after the story is complete
		$context->runtime = $runtimeConfig;

		// we need to create our user
		$context->initUser($staticConfig, $runtimeConfig, $story);

		// we need to know where to look for Prose classes
		$context->prose = array();
		if (isset($staticConfig->prose)) {
			if (!is_array($staticConfig->prose)) {
				throw new E5xx_InvalidConfig("the 'prose' section of the config must either be an array, or it must be left out");
			}

			// copy over where to look for Prose classes
			$context->prose = $staticConfig->prose;
		}

		// all done
		return $context;
	}

	public function play(StoryTeller $st, stdClass $staticConfig)
	{
		// tell the outside world what we're doing
		$this->announceStory($st);

		// the setup phases have not failed (yet!)
		$setupEnvironmentFailed = false;
		$setupTestFailed        = false;

		// setup the test environment
		$setupEnvironmentResult = $this->doTestEnvironmentSetup($st, $staticConfig);

		// setup the test itself ... but only if we have a valid
		// test environment
		if ($setupEnvironmentResult == self::SETUP_SUCCESS) {
			$setupTestResult = $this->doTestSetup($st, $staticConfig);
		}

		// work out if this story should pass or fail
		if ($setupEnvironmentResult == self::SETUP_SUCCESS && $setupTestResult == self::SETUP_SUCCESS) {
			$actionShouldWork = $this->doPreTestPrediction($st, $staticConfig);

			// capture any data before we run the test
			$this->doPreTestInspection($st, $staticConfig);

			// keep track of what happens with the action
			$actionResult = $this->doOneAction($st, $staticConfig);

			// are we happy with the test results?
			$actionWorked = $this->doPostTestInspection($st, $staticConfig);
		}
		else {
			// as the setup steps failed, we do not know what the
			// outcome of the other setups would have been
			$actionShouldWork = self::PREDICT_UNKNOWN;
			$actionResult     = self::STORY_UNKNOWN;
			$actionWorked     = self::INSPECT_UNKNOWN;
		}

		// tidy up the test, if we had a working test environment
		// to attempt to set the test up within
		if ($setupEnvironmentResult == self::SETUP_SUCCESS) {
			$this->doTestTeardown($st, $staticConfig);
		}

		// tidy up the environment, regardless of whether setting it up
		// failed or not
		$this->doTestEnvironmentTeardown($st, $staticConfig);

		// stop the browser, if it is still running
		$st->stopWebBrowser();

		// alright, so what happened?
		//
		// let's look at what should have happened, and compare it to what
		// did happen

		$resultMessage = '';
		switch ($actionShouldWork) {
			case self::PREDICT_SUCCESS:
				$resultMessage = 'expected: SUCCESS         ;';
				break;

			case self::PREDICT_FAIL:
				$resultMessage = 'expected: FAIL            ;';
				break;

			case self::PREDICT_INCOMPLETE:
				$resultMessage = 'expected: DID NOT COMPLETE;';
				break;

			case self::PREDICT_UNKNOWN:
			default:
				$resultMessage = 'expected: UNKNOWN :(      ;';
				break;
		}

		switch ($actionResult) {
			case self::STORY_COMPLETED:
				$resultMessage .= ' action: COMPLETED ;';
				break;

			case self::STORY_FAILED:
				$resultMessage .= ' action: FAILED    ;';
				break;

			case self::STORY_INCOMPLETE:
				$resultMessage .= ' action: INCOMPLETE;';
				break;

			case self::STORY_HASNOACTIONS:
				$resultMessage .= ' action: NO ACTION ;';
				break;

			default:
				$resultMessage .= ' action: UNKNOWN   ;';
		}

		switch ($actionWorked) {
			case self::INSPECT_SUCCESS:
				$resultMessage .= ' actual: SUCCESS         ;';
				break;

			case self::INSPECT_FAIL:
				$resultMessage .= ' actual: FAIL            ;';
				break;

			case self::INSPECT_INCOMPLETE:
				$resultMessage .= ' actual: DID NOT COMPLETE;';
				break;

			case self::INSPECT_UNKNOWN:
			default:
				$resultMessage .= ' actual: UNKNOWN :(      ;';
				break;
		}

		// if the action completed successfully, then the user may have
		// changed state ... let's deal with that
		if ($actionResult == self::STORY_COMPLETED && $actionWorked == self::INSPECT_SUCCESS) {
			$this->announceStage('Role Changes');
			$this->applyRoleChanges($st, $staticConfig);
		}

		$this->announceStage('Final Results');

		// to finish, mark down as PASS or FAIL
		if ($actionShouldWork == self::PREDICT_SUCCESS && ($actionResult == self::STORY_COMPLETED || $actionResult == self::STORY_HASNOACTIONS) && $actionWorked == self::INSPECT_SUCCESS) {
			$resultMessage .= ' result: PASS';
			$result = self::RESULT_PASS;
		}
		else if ($actionShouldWork == self::PREDICT_FAIL && ($actionResult == self::STORY_FAILED || $actionResult == self::STORY_HASNOACTIONS) && $actionWorked == self::INSPECT_FAIL) {
			$resultMessage .= ' result: PASS';
			$result = self::RESULT_PASS;
		}
		else if ($actionShouldWork == self::PREDICT_UNKNOWN || $actionShouldWork == self::PREDICT_INCOMPLETE) {
			$resultMessage .= ' result: UNKNOWN';
			$result = self::RESULT_UNKNOWN;
		}
		else if ($actionResult == self::STORY_INCOMPLETE || $actionResult == self::STORY_UNKNOWN) {
			$resultMessage .= ' result: UNKNOWN';
			$result = self::RESULT_UNKNOWN;
		}
		else if ($actionWorked == self::INSPECT_UNKNOWN || $actionWorked == self::INSPECT_INCOMPLETE) {
			$resultMessage .= ' result: UNKNOWN';
			$result = self::RESULT_UNKNOWN;
		}
		else {
			$resultMessage .= ' result: FAIL';
			$result = self::RESULT_FAIL;
		}

		// tell the user what happened
		Log::write(Log::LOG_NOTICE, $resultMessage);

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
		$return = self::SETUP_SUCCESS;

		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announceStage('Setup test environment');

		// do we have anything to do?
		if (!$story->hasTestEnvironmentSetup())
		{
			Log::write(Log::LOG_INFO, "story has no test environment setup instructions");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return $return;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('TestEnvironmentSetup', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test environment setup is disabled; skipping");

			// as far as the rest of the test is concerned, the setup
			// was a success
			return $return;
		}

		// get the callback to call
		$callback = $story->getTestEnvironmentSetup();

		// make the call
		try {
			$st->setCurrentPhase(self::PHASE_TESTENVIRONMENTSETUP);
			$callback($st);
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform test environment setup; " . (string)$e . "\n" . $e->getTraceAsString());
			$return = self::SETUP_FAIL;
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
		$this->announceStage('Teardown test environment');

		// do we have anything to do?
		if (!$story->hasTestEnvironmentTeardown())
		{
			Log::write(Log::LOG_INFO, "story has no test environment teardown instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('TestEnvironmentTeardown', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test environment teardown is disabled; skipping");
			return;
		}

		// get the callback to call
		$callback = $story->getTestEnvironmentTeardown();

		// make the call
		try {
			$st->setCurrentPhase(self::PHASE_TESTENVIRONMENTTEARDOWN);
			$callback($st);
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
		$return = self::SETUP_SUCCESS;

		// shorthand
		$story = $st->getStory();

		// what are we doing?
		$this->announceStage('Setup story');

		// do we have anything to do?
		if (!$story->hasTestSetup())
		{
			Log::write(Log::LOG_INFO, "story has no test setup instructions");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return $return;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('TestSetup', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test setup is disabled; skipping");

			// as far as the rest of the test is concerned, the setup was
			// a success
			return;
		}

		// setup the phase
		$st->setCurrentPhase(self::PHASE_TESTSETUP);
		$this->doPerPhaseSetup($st);

		// get the callback to call
		$callback = $story->getTestSetup();

		// make the call
		try {
			$callback($st);
		}
		catch (Exception $e)
		{
			Log::write(Log::LOG_CRITICAL, "unable to perform test setup; " . (string)$e . "\n" . $e->getTraceAsString());
			$return = self::SETUP_FAIL;
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
		$this->announceStage('Teardown story');

		// do we have anything to do?
		if (!$story->hasTestTeardown())
		{
			Log::write(Log::LOG_INFO, "story has no test teardown instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('TestTeardown', $staticConfig)) {
			Log::write(Log::LOG_INFO, "test teardown is disabled; skipping");
			return;
		}

		// get the callback to call
		$callback = $story->getTestTeardown();

		// make the call
		try {
			$st->setCurrentPhase(self::PHASE_TESTTEARDOWN);
			$callback($st);
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
		$callback = $story->getPerPhaseSetup();

		// make the call
		$callback($st);

		// all done
	}

	public function doPerPhaseTeardown(StoryTeller $st)
	{
		// shorthand
		$story = $st->getStory();

		// do we have anything to do?
		if (!$story->hasPerPhaseTeardown())
		{
			return;
		}

		// get the callback to call
		$callback = $story->getPerPhaseTeardown();

		// make the call
		$callback($st);

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
		$actionShouldWork = self::INSPECT_SUCCESS;

		try {
			$this->announceStage('Pre-test prediction');

			// do we have anything to do?
			if (!$story->hasPreTestPrediction())
			{
				Log::write(Log::LOG_INFO, "story has no pre-test prediction instructions");
				Log::write(Log::LOG_INFO, "assuming that the action should always succeed");
				return $actionShouldWork;
			}

			// should we do this stage?
			if (!$this->shouldExecuteStage('PreTestPrediction', $staticConfig)) {
				Log::write(Log::LOG_INFO, "pre-test prediction is disabled; skipping");
				Log::write(Log::LOG_INFO, "assuming that the action should always succeed");
				return $actionShouldWork;
			}

			// setup the phase
			$st->setCurrentPhase(self::PHASE_PRETESTPREDICTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$story = $st->getStory();
			$callback = $story->getPreTestPrediction();
			if (is_callable($callback)) {
				$callback($st);
			}
		}
		// in any of the expects() calls in the preflight checks fails,
		// an E5xx_ActionFailed will be thrown
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "pre-test prediction failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = self::INSPECT_FAIL;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "pre-test prediction failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = self::INSPECT_FAIL;
		}
		// if any of the tests are incomplete, deal with that too
		catch (E5xx_NotImplemented $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform pre-test prediction; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = self::INSPECT_INCOMPLETE;
		}
		// deal with the things that go wrong
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to perform pre-test prediction; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionShouldWork = self::INSPECT_UNKNOWN;
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
		$this->announceStage('Pre-test inspection');

		// do we have anything to do?
		if (!$story->hasPreTestInspection())
		{
			Log::write(Log::LOG_INFO, "story has no pre-test inspection instructions");
			return;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('PreTestInspection', $staticConfig)) {
			Log::write(Log::LOG_INFO, "pre-test inspection is disabled; skipping");
			return;
		}

		// this could all go horribly wrong ... so wrap it up and deal
		// with it if it explodes
		try {
			// do any required setup
			$st->setCurrentPhase(self::PHASE_PRETESTINSPECTION);
			$this->doPerPhaseSetup($st);

			// if the callback exists, use it
			$story = $st->getStory();
			if ($story->hasPreTestInspection()) {
				$callback = $story->getPreTestInspection();
				$callback($st);
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
		$actionResult = self::STORY_COMPLETED;

		// tell the user what we are doing
		$this->announceStage('Action');

		// do we have anything to do?
		if (!$story->hasActions())
		{
			Log::write(Log::LOG_INFO, "story has no action instructions");
			return self::STORY_HASNOACTIONS;
		}

		// should we do this stage?
		if (!$this->shouldExecuteStage('PreTestInspection', $staticConfig)) {
			Log::write(Log::LOG_INFO, "actions are disabled; skipping");
			return self::STORY_HASNOACTIONS;
		}

		// run ONE of the actions, picked at random
		try {

			// do any setup
			$st->setCurrentPhase(self::PHASE_ACTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$action = $story->getOneAction();
			$action($st);
		}

		// if the set of actions fails, it will throw this exception
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "action failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionResult = self::STORY_FAILED;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "action failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionResult = self::STORY_FAILED;
		}
		// deal with the things that go wrong ... but do NOT bail out,
		// because we need to run the postflight checks no matter what!
		catch (E5xx_NotImplemented $e) {
			// log what happened
			Log::write(Log::LOG_CRITICAL, "unable to complete actions; " . (string)$e . "\n" . $e->getTraceAsString());

			// mark this story as incomplete
			$actionResult = self::STORY_INCOMPLETE;
		}
		catch (Exception $e) {
			// log what happened
			Log::write(Log::LOG_CRITICAL, "unable to complete actions; " . (string)$e . "\n" . $e->getTraceAsString());

			// mark this story as failed
			$actionResult = self::STORY_FAILED;
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
		$actionWorked = self::INSPECT_SUCCESS;

		try {
			$this->announceStage('Post-test inspection');

			// do we have anything to do?
			if (!$story->hasPostTestInspection())
			{
				Log::write(Log::LOG_INFO, "story has no post-test inspection instructions");
				Log::write(Log::LOG_WARNING, "assuming that the action was successful (dangerous!)");
				return $actionWorked;
			}

			// should we do this stage?
			if (!$this->shouldExecuteStage('PostTestInspection', $staticConfig)) {
				Log::write(Log::LOG_INFO, "post-test inspection is disabled; skipping");
				Log::write(Log::LOG_WARNING, "assuming that the action was successful (dangerous!)");
				return $actionWorked;
			}

			// do any necessary setup
			$st->setCurrentPhase(self::PHASE_POSTTESTINSPECTION);
			$this->doPerPhaseSetup($st);

			// make the call
			$story = $st->getStory();
			$callback = $story->getPostTestInspection();
			if (is_callable($callback)) {
				$callback($st);
			}
		}
		catch (E5xx_ActionFailed $e) {
			Log::write(Log::LOG_CRITICAL, "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = self::INSPECT_FAIL;
		}
		catch (E5xx_ExpectFailed $e) {
			Log::write(Log::LOG_CRITICAL, "post-test inspection failed; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = self::INSPECT_FAIL;
		}
		catch (E5xx_NotImplemented $e) {
			Log::write(Log::LOG_CRITICAL, "unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = self::INSPECT_INCOMPLETE;
		}
		catch (Exception $e) {
			Log::write(Log::LOG_CRITICAL, "unable to complete post-test inspection; " . (string)$e . "\n" . $e->getTraceAsString());
			$actionWorked = self::INSPECT_UNKNOWN;
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
		$callback = $story->getRoleChanges();
		return $callback($st);
	}

	public function announceStory(StoryTeller $st)
	{
		$story = $st->getStory();

		echo "\n";
		echo "=============================================================\n";
		echo "\n";
		echo "Story   : " . $story->getName() . "\n";
		echo "Category: " . $story->getCategory() . "\n";
		echo "Group   : " . $story->getGroup() . "\n";
		echo "\n";
	}

	public function announceStage($stageName)
	{
		echo "-------------------------------------------------------------\n";
		echo "Now performing: $stageName\n";
		echo "\n";
	}

	public function shouldExecuteStage($stageName, stdClass $staticConfig)
	{
		if (!isset($staticConfig->stages, $staticConfig->stages->$stageName) || !$staticConfig->stages->$stageName)
		{
			return false;
		}

		return true;
	}
}