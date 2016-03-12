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

$story->addAction(function() use($story) {
	$checkpoint = Checkpoint::getCheckpoint();

	// these should pass
	Asserts::assertsObject($story)->isInstanceOf('DataSift\Storyplayer\PlayerLib\Story');
	Asserts::assertsObject($checkpoint)->isInstanceOf('DataSift\Storyplayer\PlayerLib\Story_Checkpoint');

	// and this should fail
	try {
		Asserts::assertsObject($story)->isInstanceOf('Prose\Prose');
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