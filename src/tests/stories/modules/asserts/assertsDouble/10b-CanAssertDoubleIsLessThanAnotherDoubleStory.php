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
	$actualData = 1.12;
	$expectedData1 = 1.13;
	Asserts::assertsDouble($actualData)->isLessThan($expectedData1);

	// and these should fail
	try {
		$expectedData2 = $actualData;
		Asserts::assertsDouble($actualData)->isLessThan($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	try {
		$expectedData3 = 1.11;
		Asserts::assertsDouble($actualData)->isLessThan($expectedData3);
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

	Asserts::assertsObject($checkpoint)->hasAttribute("test2Passed");
	Asserts::assertsBoolean($checkpoint->test2Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute("test3Passed");
	Asserts::assertsBoolean($checkpoint->test3Passed)->isTrue();
});