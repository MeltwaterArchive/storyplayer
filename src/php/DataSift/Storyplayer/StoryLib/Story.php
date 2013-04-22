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
 * @category    Libraries
 * @package     Storyplayer
 * @subpackage  StoryLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\StoryLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\EnvironmentSetup;

/**
 * Object that represents a single story
 *
 * @category   Libraries
 * @package    Storyplayer
 * @subpackage StoryLib
 * @author     Stuart Herbert <stuart.herbert@datasift.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://datasift.github.io/storyplayer
 */
class Story
{
	/**
	 * the category that this story belongs to
	 * @var string
	 */
	protected $category;

	/**
	 * the group that this story belongs to
	 * @var string
	 */
	protected $group;

	/**
	 * the name of this story
	 * @var string
	 */
	protected $name;

	/**
	 * A list of the users who should be allowed to perform this story
	 *
	 * Many stories are only valid for a subset of all potential users.
	 * This is a list of all user _roles_ that the story is written for.
	 *
	 * This is basically a special kind of hint that Storyplayer uses
	 * when picking the next story to play.
	 *
	 * @var array(string)
	 */
	protected $validRoles = array();

	/**
	 * the function that provides hints about how this story changes
	 * the state of the system or user
	 *
	 * @var callable
	 */
	protected $hintsCallback;

	/**
	 * the function that provides any (optional) environment setup work
	 *
	 * @var callable
	 */
	protected $testEnvironmentSetupCallback;

	/**
	 * the function that provides any story-specific setup work
	 *
	 * @var callable
	 */
	protected $testSetupCallback;

	/**
	 * the function that provides any story-specific teardown action
	 * @var callable
	 */
	protected $testTeardownCallback;

	/**
	 * the function that provides any (optional) environment teardown action
	 * @var callable
	 */
	protected $testEnvironmentTeardownCallback;

	/**
	 * the function that provides any story-specific setup work that
	 * happens before each phase of the test
	 * @var callable
	 */
	protected $perPhaseSetupCallback;

	/**
	 * the function that provides any story-specific teardown work that
	 * happens at the end of each phase of the test
	 * @var callable
	 */
	protected $perPhaseTeardownCallback;

	/**
	 * the function that provides information about how this story has
	 * changed the state of the system or user
	 *
	 * @var callable
	 */
	protected $roleChangesCallback;

	/**
	 * the callback that dynamically determines in advance whether the
	 * story actions should succeed or fail
	 *
	 * @var callable
	 */
	protected $preTestPredictionCallback;

	/**
	 * the callback that dynamically determines afterwards whether or not
	 * the story actions actually did succeed
	 *
	 * @var callable
	 */
	protected $reportTestResultsCallback;

	/**
	 * the callback used to remember the state of the system *before*
	 * the action occurs
	 *
	 * @var callable
	 */
	protected $preTestInspectionCallback;

	/**
	 * the actions that execute the story on behalf of the user
	 *
	 * this is an array of callbacks.  Each callback is a single set of
	 * actions to execute the story.  Each callback is an alternative way
	 * to execute the story.  Each callback is meant to be equivalent; ie
	 * they achieve the same thing, just in different ways.
	 *
	 * If any of the callbacks has different outcomes, then they belong
	 * in separate user stories. NO EXCEPTIONS. This is a fundamental
	 * assumption of Storyplayer; ignore it, and Storyplayer's no use to
	 * you!
	 *
	 * @var array
	 */
	protected $actionsCallbacks = array();

	// ====================================================================
	//
	// Metadata about the story itself
	//
	// --------------------------------------------------------------------

	public function __construct()
	{
		// assume we are testing a website
		$this->setUsesTheWebBrowser();

		// set default callbacks up
		$this->setDefaultCallbacks();
	}

	public function inGroup($groupName)
	{
		$this->setGroup($groupName);

		return $this;
	}

	public function called($userStoryText)
	{
		$this->setName($userStoryText);

		return $this;
	}

	/**
	 * Get the category that this story belongs to
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @return string the category that this story belongs to
	 */
	public function getCategory()
	{
	    return $this->category;
	}

	/**
	 * Set the category that this story belongs to
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @param  string $category the category that this story belongs to
	 * @return Story  $this
	 */
	public function setCategory($newCategory)
	{
	    $this->category = $newCategory;
	    return $this;
	}

	/**
	 * Get the group that this story belongs to
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @return string the group that this story belongs to
	 */
	public function getGroup()
	{
	    return $this->group;
	}

	/**
	 * Set the group that this story belongs to
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @param  string $group the group that this story belongs to
	 * @return Story  $this
	 */
	public function setGroup($newGroup)
	{
	    $this->group = $newGroup;
	    return $this;
	}

	/**
	 * Get the name of this story
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @return string the name of this story
	 */
	public function getName()
	{
	    return $this->name;
	}

	/**
	 * Set the name of this story
	 *
	 * Systems under test can grow to encompass hundreds, if not thousands
	 * of user stories.  To make this manageable at scale, we break down
	 * each user story like this:
	 *
	 * Name    : Starts as a free user with 10 USD in credit
	 * Category: Billing User Stories
	 * Group   : User States
	 *
	 * The 'name' is the summary text of the user story itself, which
	 * should be no longer than a single sentence, please.
	 *
	 * The 'category' is the general group that the user story belongs to.
	 * These are the top-level groups, such as 'Registration', 'Billing'
	 * and so forth.
	 *
	 * The 'group' is the specific group _inside_ the category that the
	 * user story belongs to.  The groups are specific to the category.
	 *
	 * @param  string $name the name of this story
	 * @return Story  $this
	 */
	public function setName($newName)
	{
	    $this->name = $newName;
	    return $this;
	}

	// ====================================================================
	//
	// Metadata for which states the story is valid for
	//
	// --------------------------------------------------------------------

	public function addValidRole($role)
	{
		$this->validRoles[$role] = $role;

		return $this;
	}

	/**
	 * Synonym for addValidRole()
	 *
	 * @see Story::addValidRole
	 */
	public function andValidRole($role)
	{
		$this->validRoles[$role] = $role;

		return $this;
	}

	public function hasRole($roleName)
	{
		return isset($this->validRoles[$roleName]);
	}

	// ====================================================================
	//
	// Support for changing roles after a test has succeeded
	//
	// --------------------------------------------------------------------

	/**
	 * get the role changes callback
	 *
	 * @return callback
	 */
	public function getRoleChanges()
	{
	    return $this->roleChangesCallback;
	}

	/**
	 * has the role changes callback been set?
	 *
	 * @return boolean true if the callback has been set
	 */
	public function hasRoleChanges()
	{
		return (is_callable($this->roleChangesCallback));
	}

	public function setRoleChanges($newCallback)
	{
		$this->roleChangesCallback = $newCallback;
	}

	// ====================================================================
	//
	// Information about how to setup and teardown the test environment
	//
	// --------------------------------------------------------------------

	/**
	 * set up an entire environment from a predefined class
	 *
	 * @return void
	 */
	public function setEnvironment(EnvironmentSetup $env)
	{
		$env->setStory($this);
		$this->setTestEnvironmentSetup(array($env, "setUp"));
		$this->setTestEnvironmentTeardown(array($env, "tearDown"));
	}

	/**
	 * get the callback for per-environment setup work
	 *
	 * @return $callback
	 */
	public function getTestEnvironmentSetup()
	{
	    return $this->testEnvironmentSetupCallback;
	}

	/**
	 * do we have a per-environment setup callback?
	 *
	 * @return boolean true if there is a per-environment setup callback
	 */
	public function hasTestEnvironmentSetup()
	{
		return (is_callable($this->testEnvironmentSetupCallback));
	}

	public function setTestEnvironmentSetup($newCallback)
	{
		$this->testEnvironmentSetupCallback = $newCallback;
	}

	/**
	 * get the callback for per-environment teardown work
	 *
	 * @return $callback
	 */
	public function getTestEnvironmentTeardown()
	{
	    return $this->testEnvironmentTeardownCallback;
	}

	/**
	 * do we have a per-environment teardown callback?
	 *
	 * @return boolean true if there is a per-environment teardown callback
	 */
	public function hasTestEnvironmentTeardown()
	{
		return (is_callable($this->testEnvironmentTeardownCallback));
	}

	public function setTestEnvironmentTeardown($newCallback)
	{
		$this->testEnvironmentTeardownCallback = $newCallback;
	}

	// ====================================================================
	//
	// Information about how to setup and teardown the test
	//
	// --------------------------------------------------------------------

	/**
	 * get the callback for per-story setup work
	 *
	 * @return $callback
	 */
	public function getTestSetup()
	{
	    return $this->testSetupCallback;
	}

	/**
	 * do we have a pre-story setup callback?
	 *
	 * @return boolean true if there is a pre-story setup callback
	 */
	public function hasTestSetup()
	{
		return (is_callable($this->testSetupCallback));
	}

	public function setTestSetup($newCallback)
	{
		$this->testSetupCallback = $newCallback;
	}

	/**
	 * get the callback for post-story teardown work
	 *
	 * @return $callback
	 */
	public function getTestTeardown()
	{
	    return $this->testTeardownCallback;
	}

	/**
	 * do we have a post-story teardown callback?
	 *
	 * @return boolean true if there is a post-story teardown callback
	 */
	public function hasTestTeardown()
	{
		return (is_callable($this->testTeardownCallback));
	}


	public function setTestTeardown($newCallback)
	{
		$this->testTeardownCallback = $newCallback;
	}

	// ====================================================================
	//
	// Actions to happen before and after every phase of the test
	//
	// --------------------------------------------------------------------

	/**
	 * get the callback for per-phase setup work
	 *
	 * @return $callback
	 */
	public function getPerPhaseSetup()
	{
	    return $this->perPhaseSetupCallback;
	}

	/**
	 * do we have a per-phase setup callback?
	 *
	 * @return boolean true if there is a per-phase setup callback
	 */
	public function hasPerPhaseSetup()
	{
		return (is_callable($this->perPhaseSetupCallback));
	}


	public function setPerPhaseSetup($newCallback)
	{
		$this->perPhaseSetupCallback = $newCallback;
	}

	/**
	 * get the callback for per-phase teardown work
	 *
	 * @return $callback
	 */
	public function getPerPhaseTeardown()
	{
	    return $this->perPhaseTeardownCallback;
	}

	/**
	 * do we have a per-phase teardown callback?
	 *
	 * @return boolean true if there is a per-phase teardown callback
	 */
	public function hasPerPhaseTeardown()
	{
		return (is_callable($this->perPhaseTeardownCallback));
	}

	public function setPerPhaseTeardown($newCallback)
	{
		$this->perPhaseTeardownCallback = $newCallback;
	}

	// ====================================================================
	//
	// Information about how the story changes the system
	//
	// --------------------------------------------------------------------

	/**
	 * get the hints callback
	 *
	 * @return callback
	 */
	public function getHints()
	{
	    return $this->hintsCallback;
	}

	/**
	 * have any hints been set?
	 *
	 * @return boolean true if the callback has been set
	 */
	public function hasHints()
	{
		return (is_callable($this->hintsCallback));
	}

	public function setHints($newCallback)
	{
		$this->hintsCallback = $newCallback;
	}

	// ====================================================================
	//
	// Before and after tests
	//
	// --------------------------------------------------------------------

	/**
	 * get the callback to use to perform the preflight checks
	 *
	 * @return callback
	 */
	public function getPreTestPrediction()
	{
	    return $this->preTestPredictionCallback;
	}

	/**
	 * do we have a callback
	 * @return boolean [description]
	 */
	public function hasPreTestPrediction()
	{
		return is_callable($this->preTestPredictionCallback);
	}

	public function setPreTestPrediction($newCallback)
	{
		$this->preTestPredictionCallback = $newCallback;
	}

	// ====================================================================
	//
	// Checkpoint the relevant system state before actions occur
	//
	// --------------------------------------------------------------------

	/**
	 * get the callback to use to perform the preflight checkpoint
	 *
	 * @return callback
	 */
	public function getPreTestInspection()
	{
	    return $this->preTestInspectionCallback;
	}

	/**
	 * do we have a callback
	 * @return boolean [description]
	 */
	public function hasPreTestInspection()
	{
		return is_callable($this->preTestInspectionCallback);
	}

	public function setPreTestInspection($newCallback)
	{
		$this->preTestInspectionCallback = $newCallback;
	}

	// ====================================================================
	//
	// Add in the actions that make the story come to life
	//
	// --------------------------------------------------------------------

	public function addAction($newCallback)
	{
		$this->actionsCallbacks[] = $newCallback;
	}

	public function addActions($newCallback)
	{
		$this->actionsCallbacks[] = $newCallback;
	}

	/**
	 * pick one action at random, and return it to the caller
	 *
	 * @return callback(StoryContext)
	 */
	public function getOneAction()
	{
		// do we have any callbacks to pick from?
		if (count($this->actionsCallbacks) == 0)
		{
			throw new E5xx_NoStoryActions($this->getName());
		}

		// pick one story
		$i = rand(0, count($this->actionsCallbacks) - 1);

		// return it to the caller
		return $this->actionsCallbacks[$i];
	}

	/**
	 * does this story have any actions?
	 *
	 * @return boolean true if this story has any actions
	 */
	public function hasActions()
	{
		return (count($this->actionsCallbacks) > 0);
	}

	// ====================================================================
	//
	// Determine whether the test passed or failed
	//
	// --------------------------------------------------------------------

	/**
	 * get the callback to use to work out the test results
	 *
	 * @return callback
	 */
	public function getPostTestInspection()
	{
	    return $this->postTestInspectionCallback;
	}

	public function hasPostTestInspection()
	{
		return is_callable($this->postTestInspectionCallback);
	}

	public function setPostTestInspection($newCallback)
	{
		$this->postTestInspectionCallback = $newCallback;
	}

	// ====================================================================
	//
	// Our default behaviour when the story object is instantiated
	//
	// --------------------------------------------------------------------

	public function setDefaultCallbacks()
	{
		// 1: environment setup
		$this->setTestEnvironmentSetup(function(StoryTeller $st) {
			$st->usingReporting()->reportNotRequired();
		});

		// 2: test setup
		$this->setTestSetup(function(StoryTeller $st) {
			$st->usingReporting()->reportNotRequired();
		});

		// 3: pre-test prediction
		$this->setPreTestPrediction(function(StoryTeller $st) {
			$st->usingReporting()->reportShouldAlwaysSucceed();
		});

		// 4: pre-test inspection
		$this->setPreTestInspection(function(StoryTeller $st) {
			$st->usingReporting()->reportNotRequired();
		});

		// 5: test action
		//
		// we set no default for this, because we do not want the action
		// to be chosen by StoryPlayer
		//
		// (StoryPlayer chooses one action at random from the set of
		// supplied actions)

		// 6: post-test inspection
		//
		// we set no default for this, because each story must provide
		// this

		// 7: test tear down
		$this->setTestEnvironmentSetup(function(StoryTeller $st) {
			$st->usingReporting()->reportNotRequired();
		});

		// 8: environment teardown
		$this->setTestEnvironmentTeardown(function(StoryTeller $st) {
			$st->usingReporting()->reportNotRequired();
		});
	}

	// ====================================================================
	//
	// Serialisation and other format convertors
	//
	// --------------------------------------------------------------------

	/**
	 * return a string representation of the story, for things like logging
	 * @return string
	 */
	public function __toString()
	{
		return $this->getCategory() . ' :: ' . $this->getGroup() . ' :: ' . $this->getName();
	}

	// ==================================================================
	//
	// Helpers to speed up writing tests
	//
	// ------------------------------------------------------------------

	/**
	 * configures this story to start a web browser before each phase of
	 * the test, and to stop the web browser after each phase
	 */
	public function setUsesTheWebBrowser()
	{
		// start the browser before each phase
		$this->setPerPhaseSetup(function(StoryTeller $st) {
			$st->startWebBrowser();
		});

		// stop the brownser after each phase
		$this->setPerPhaseTeardown(function(StoryTeller $st) {
			$st->stopWebBrowser();
		});
	}

	/**
	 * configures this story with empty per-phase setup/teardown
	 */
	public function setDoesntUseTheWebBrowser()
	{
		$this->setPerPhaseSetup(function(StoryTeller $st) {
			// do nothing
		});

		$this->setPerPhaseTeardown(function(StoryTeller $st) {
			// do nothing
		});
	}
}
