<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Stories\BuildStory;

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
	$integerData = 1;
	Asserts::assertsInteger($integerData)->isInteger();

	// and these should fail
	try {
		$nullData = null;
		Asserts::assertsInteger($nullData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		Asserts::assertsInteger($arrayData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		Asserts::assertsInteger($booleanData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		Asserts::assertsInteger($booleanData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		Asserts::assertsInteger($doubleData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		Asserts::assertsInteger($doubleData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$objectData = new stdClass;
		Asserts::assertsInteger($objectData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	try {
		$stringData = "";
		Asserts::assertsInteger($stringData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->stringTestPassed = true;
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

	Asserts::assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	Asserts::assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("stringTestPassed");
	Asserts::assertsBoolean($checkpoint->stringTestPassed)->isTrue();
});