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

$story->addAction(function() use($story) {
	$checkpoint = Checkpoint::getCheckpoint();

	// this should pass
	Asserts::assertsObject($story)->hasMethod('requiresStoryplayerVersion');

	// and this should fail
	try {
		Asserts::assertsObject($story)->hasMethod('newStoryFor');
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