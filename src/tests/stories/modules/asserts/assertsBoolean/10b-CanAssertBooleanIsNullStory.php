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
	$checkpoint->test2Passed = false;
	$checkpoint->test3Passed = false;

	// this should pass
	$testData1 = null;
	Asserts::assertsBoolean($testData1)->isNull();

	// these should all fail
	$testData2 = true;
	try {
		Asserts::assertsBoolean($testData2)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	$testData3 = false;
	try {
		Asserts::assertsBoolean($testData3)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute('test2Passed');
	Asserts::assertsBoolean($checkpoint->test2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('test3Passed');
	Asserts::assertsBoolean($checkpoint->test3Passed)->isTrue();

});