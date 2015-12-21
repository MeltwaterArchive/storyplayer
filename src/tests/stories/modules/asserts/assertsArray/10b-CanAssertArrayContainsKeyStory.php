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
	$testData = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d"
	];
	Asserts::assertsArray($testData)->hasKey("alpha");

	// and this should fail
	$checkpoint->test2Exception = false;
	try {
		Asserts::assertsArray($testData)->hasKey("zulu");
	}
	catch (Exception $e) {
		$checkpoint->test2Exception = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute("test2Exception");
	Asserts::assertsBoolean($checkpoint->test2Exception)->isTrue();
});