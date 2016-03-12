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
	$checkpoint->test3Passed = false;
	$checkpoint->test4Passed = false;

	// this should pass
	$expected1 = false;
	$actual1   = true;
	Asserts::assertsBoolean($expected1)->doesNotEqual($actual1);

	$expected2 = true;
	$actual2   = false;
	Asserts::assertsBoolean($expected2)->doesNotEqual($actual2);

	// these should all fail
	$expected3 = true;
	$actual3   = true;
	try {
		Asserts::assertsBoolean($expected3)->doesNotEqual($actual3);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	$expected4 = false;
	$actual4   = false;
	try {
		Asserts::assertsBoolean($expected4)->doesNotEqual($actual4);
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute('test3Passed');
	Asserts::assertsBoolean($checkpoint->test3Passed)->isTrue();

	Asserts::assertsObject($checkpoint)->hasAttribute('test4Passed');
	Asserts::assertsBoolean($checkpoint->test4Passed)->isTrue();
});