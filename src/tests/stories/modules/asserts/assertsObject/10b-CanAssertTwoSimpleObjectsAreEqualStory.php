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

	// create a different object that uses the same data
	$expectedData1 = new stdClass;
	$expectedData1->attribute1 = "hello";
	$expectedData1->attribute2 = "world";
	Asserts::assertsObject($actualData)->equals($expectedData1);

	// create a different object that uses the same data in a different order
	$expectedData2 = new stdClass;
	$expectedData2->attribute2 = "world";
	$expectedData2->attribute1 = "hello";
	Asserts::assertsObject($actualData)->equals($expectedData2);

	// and this should fail
	try {
		$expectedData3 = new stdClass;
		$expectedData3->attribute1 = "hello";
		$expectedData3->attribute2 = "world";
		$expectedData3->attribute3 = "and welcome!";
		Asserts::assertsObject($actualData)->equals($expectedData3);
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