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

	// these should pass
	$actualData = new stdClass;
	$actualData->attribute1 = null;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = [];
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = true;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = false;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 0.0;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 3.1415927;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 0;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 99;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = $checkpoint;
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = "";
	Asserts::assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = "hello, Storyplayer";
	Asserts::assertsObject($actualData)->isNotEmpty();

	// and this should fail
	$actualData = new stdClass;
	try {
		Asserts::assertsObject($actualData)->isNotEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute("test1Passed");
	Asserts::assertsBoolean($checkpoint->test1Passed)->equals(true);
});