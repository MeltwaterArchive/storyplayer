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
	$testData1 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d"
	];
	$testData2 = [ 1, 2, 3, 4 ];
	Asserts::assertsArray($testData1)->isSameLengthAs($testData2);

	// and these should fail
	$testData3 = [ 1 ];

	$checkpoint->test2Exception = false;
	try {
		Asserts::assertsArray($testData3)->isSameLengthAs($testData1);
	}
	catch (Exception $e) {
		$checkpoint->test2Exception = true;
	}

	$checkpoint->test3Exception = false;
	try {
		Asserts::assertsArray($testData1)->isSameLengthAs($testData3);
	}
	catch (Exception $e) {
		$checkpoint->test3Exception = true;
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

	Asserts::assertsObject($checkpoint)->hasAttribute("test3Exception");
	Asserts::assertsBoolean($checkpoint->test3Exception)->isTrue();
});