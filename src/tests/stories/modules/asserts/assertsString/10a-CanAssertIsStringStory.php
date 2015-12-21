<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	// this should pass
	$stringData = "";
	Asserts::assertsString($stringData)->isString();

	$stringData = "hello, Storyplayer";
	Asserts::assertsString($stringData)->isString();

	// and these should fail
	try {
		$nullData = null;
		Asserts::assertsString($nullData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		Asserts::assertsString($arrayData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		Asserts::assertsString($booleanData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		Asserts::assertsString($booleanData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		Asserts::assertsString($doubleData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		Asserts::assertsString($doubleData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$intData = 0;
		Asserts::assertsString($intData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->intTest1Passed = true;
	}

	try {
		$intData = 1;
		Asserts::assertsString($intData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->intTest2Passed = true;
	}

	try {
		$objectData = $checkpoint;
		Asserts::assertsString($objectData)->isString();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute("nullTestPassed");
	Asserts::assertsBoolean($checkpoint->nullTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("arrayTestPassed");
	Asserts::assertsBoolean($checkpoint->arrayTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("booleanTest1Passed");
	Asserts::assertsBoolean($checkpoint->booleanTest1Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("booleanTest2Passed");
	Asserts::assertsBoolean($checkpoint->booleanTest2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("doubleTest1Passed");
	Asserts::assertsBoolean($checkpoint->doubleTest1Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("doubleTest2Passed");
	Asserts::assertsBoolean($checkpoint->doubleTest2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("intTest1Passed");
	Asserts::assertsBoolean($checkpoint->intTest1Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("intTest2Passed");
	Asserts::assertsBoolean($checkpoint->intTest2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	Asserts::assertsBoolean($checkpoint->objectTestPassed)->isTrue();
});