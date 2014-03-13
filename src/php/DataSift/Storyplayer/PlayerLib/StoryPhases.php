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

/**
 * container for metadata about story phases
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryPhases
{
	const PHASE_TESTENVIRONMENTSETUP = 1;
	const PHASE_TESTSETUP = 2;
	const PHASE_PRETESTPREDICTION = 3;
	const PHASE_PRETESTINSPECTION = 4;
	const PHASE_ACTION = 5;
	const PHASE_POSTTESTINSPECTION = 6;
	const PHASE_TESTTEARDOWN = 7;
	const PHASE_TESTENVIRONMENTTEARDOWN = 8;
	const PHASE_ROLECHANGES = 9;
	const PHASE_RESULTS = 10;

	static public $phaseToName = array(
		1  => "TestEnvironmentSetup",
		2  => "TestSetup",
		3  => "PreTestPrediction",
		4  => "PreTestInspection",
		5  => "Action",
		6  => "PostTestInspection",
		7  => "TestTeardown",
		8  => "TestEnvironmentTeardown",
		9  => "RoleChanges",
		10 => "Results"
	);

	static public $phaseToText = array(
		1  => "Test Environment Setup",
		2  => "Test Setup",
		3  => "Pre-Test Prediction",
		4  => "Pre-Test Inspection",
		5  => "Action",
		6  => "Post-Test Inspection",
		7  => "Test Teardown",
		8  => "Test Environment Teardown",
		9  => "Role Changes",
		10 => "Results"
	);

	static public $phaseToClass = array(
		1  => "TestEnvironmentSetupPhase",
		2  => "TestSetupPhase",
		3  => "PreTestPredictionPhase",
		4  => "PreTestInspectionPhase",
		5  => "ActionPhase",
		6  => "PostTestInspectionPhase",
		7  => "TestTeardownPhase",
		8  => "TestEnvironmentTeardownPhase",
		9  => "RoleChangesPhase",
		10 => "ResultsPhase",
	);
}