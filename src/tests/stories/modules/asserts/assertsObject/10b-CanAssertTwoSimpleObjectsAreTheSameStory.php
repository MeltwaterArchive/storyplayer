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

	// we'll use this in our comparisons
	$actualData = new stdClass;
	$actualData->attribute1 = "hello";
	$actualData->attribute2 = "world";

	// this should pass
	$expectedData1 = $actualData;
	Asserts::assertsObject($actualData)->isSameAs($expectedData1);

	// and these should fail

	try {
		$expectedData2 = new stdClass;
		$expectedData2->attribute1 = "hello";
		$expectedData2->attribute2 = "world";
		Asserts::assertsObject($actualData)->isSameAs($expectedData2);
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