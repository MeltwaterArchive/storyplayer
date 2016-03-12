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
	$objectData = new stdClass;
	Asserts::assertsObject($objectData)->isObject();

	// and these should fail
	try {
		$nullData = null;
		Asserts::assertsObject($nullData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		Asserts::assertsObject($arrayData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		Asserts::assertsObject($booleanData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		Asserts::assertsObject($booleanData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		Asserts::assertsObject($doubleData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		Asserts::assertsObject($doubleData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$intData = 0;
		Asserts::assertsObject($intData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->intTest1Passed = true;
	}

	try {
		$intData = 1;
		Asserts::assertsObject($intData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->intTest2Passed = true;
	}

	try {
		$stringData = "";
		Asserts::assertsObject($stringData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->stringTest1Passed = true;
	}

	try {
		$stringData = "mary had a little lamb";
		Asserts::assertsObject($stringData)->isObject();
	}
	catch (Exception $e) {
		$checkpoint->stringTest2Passed = true;
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

	Asserts::assertsObject($checkpoint)->hasAttribute("stringTest1Passed");
	Asserts::assertsBoolean($checkpoint->stringTest1Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("stringTest2Passed");
	Asserts::assertsBoolean($checkpoint->stringTest2Passed)->isTrue();
});