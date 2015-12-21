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

	// these should pass
	$actualData = 1.12;
	$expectedData1 = 1.11;
	Asserts::assertsDouble($actualData)->isGreaterThanOrEqualTo($expectedData1);

	$actualData = 1.12;
	$expectedData2 = 1.12;
	Asserts::assertsDouble($actualData)->isGreaterThanOrEqualTo($expectedData2);

	// and this should fail
	try {
		$expectedData3 = 1.2;
		Asserts::assertsDouble($actualData)->isGreaterThan($expectedData3);
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

	Asserts::assertsObject($checkpoint)->hasAttribute("test3Passed");
	Asserts::assertsBoolean($checkpoint->test3Passed)->isTrue();
});