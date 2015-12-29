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

	// these should pass
	Asserts::assertsObject($story)->isNotInstanceOf('Prose\Prose');

	// and these should fail
	try {
		Asserts::assertsObject($story)->isNotInstanceOf('DataSift\Storyplayer\PlayerLib\Story');
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		Asserts::assertsObject($checkpoint)->isNotInstanceOf('DataSift\Storyplayer\PlayerLib\Story_Checkpoint');
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

	Asserts::assertsObject($checkpoint)->hasAttribute("test1Passed");
	Asserts::assertsBoolean($checkpoint->test1Passed)->equals(true);

	Asserts::assertsObject($checkpoint)->hasAttribute("test2Passed");
	Asserts::assertsBoolean($checkpoint->test2Passed)->equals(true);

});
