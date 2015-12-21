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
	$goodData1 = true;
	Asserts::assertsBoolean($goodData1)->isBoolean();

	// these should all fail
	$nullTestData = null;
	try {
		Asserts::assertsBoolean($nullTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	$arrayTestData = [ 1, 2 ];
	try {
		Asserts::assertsBoolean($arrayTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	$doubleTestData = 1.1;
	try {
		Asserts::assertsBoolean($doubleTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->doubleTestPassed = true;
	}

	$intTestData = 1;
	try {
		Asserts::assertsBoolean($intTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->intTestPassed = true;
	}

	$objectTestData = new stdClass;
	try {
		Asserts::assertsBoolean($objectTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	$stringTestData = "hello, Storyplayer!";
	try {
		Asserts::assertsBoolean($stringTestData)->isBoolean();
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

	Asserts::assertsObject($checkpoint)->hasAttribute('nullTestPassed');
	Asserts::assertsBoolean($checkpoint->nullTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('arrayTestPassed');
	Asserts::assertsBoolean($checkpoint->arrayTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('intTestPassed');
	Asserts::assertsBoolean($checkpoint->intTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('doubleTestPassed');
	Asserts::assertsBoolean($checkpoint->doubleTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('objectTestPassed');
	Asserts::assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('stringTestPassed');
	Asserts::assertsBoolean($checkpoint->stringTestPassed)->isTrue();

});