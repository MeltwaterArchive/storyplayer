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
	$doubleData = 1.1;
	Asserts::assertsDouble($doubleData)->isDouble();

	// and these should fail
	try {
		$nullData = null;
		Asserts::assertsDouble($nullData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		Asserts::assertsDouble($arrayData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		Asserts::assertsDouble($booleanData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		Asserts::assertsDouble($booleanData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$intData = 0;
		Asserts::assertsDouble($intData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->intTest1Passed = true;
	}

	try {
		$intData = 11;
		Asserts::assertsDouble($intData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->intTest2Passed = true;
	}

	try {
		$objectData = new stdClass;
		Asserts::assertsDouble($objectData)->isDouble();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	try {
		$stringData = "";
		Asserts::assertsDouble($stringData)->isDouble();
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

	Asserts::assertsObject($checkpoint)->hasAttribute("intTest1Passed");
	Asserts::assertsBoolean($checkpoint->intTest1Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("intTest2Passed");
	Asserts::assertsBoolean($checkpoint->intTest2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	Asserts::assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("stringTestPassed");
	Asserts::assertsBoolean($checkpoint->stringTestPassed)->isTrue();
});
