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
	$actualData = 2;
	$expectedData1 = 3.0;
	Asserts::assertsInteger($actualData)->isLessThan($expectedData1);

	// and these should fail
	try {
		$expectedData2 = 2.0;
		Asserts::assertsInteger($actualData)->isLessThan($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute("test2Passed");
	Asserts::assertsBoolean($checkpoint->test2Passed)->isTrue();
});