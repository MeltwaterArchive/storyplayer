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

	// this should pass
	$expectedData1 = 1.1;
	$actualData1 = 1.1;
	Asserts::assertsDouble($actualData1)->equals($expectedData1);

	// and these should fail
	try {
		$expectedData2 = 1.12;
		Asserts::assertsDouble($actualData1)->equals($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	// and these should fail
	try {
		$expectedData3 = 0;
		Asserts::assertsDouble($actualData1)->equals($expectedData3);
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