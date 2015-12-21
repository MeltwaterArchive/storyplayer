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
	$checkpoint->test3Passed = false;

	// this should pass
	$testData1 = true;
	Asserts::assertsBoolean($testData1)->isNotNull();

	$testData2 = false;
	Asserts::assertsBoolean($testData2)->isNotNull();

	// these should all fail
	$testData3 = null;
	try {
		Asserts::assertsBoolean($testData3)->isNotNull();
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

	Asserts::assertsObject($checkpoint)->hasAttribute('test3Passed');
	Asserts::assertsBoolean($checkpoint->test3Passed)->isTrue();

});