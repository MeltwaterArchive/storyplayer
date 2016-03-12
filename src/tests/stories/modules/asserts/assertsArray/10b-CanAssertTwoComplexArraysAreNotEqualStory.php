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
	$testData1 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d",
		"echo"    => [ 1, 2, 3, 4, 5 ],
	];
	$testData2 = [
		"alpha"   => "1",
		"bravo"   => "2",
		"charlie" => "3",
		"delta"   => "4",
		"echo"    => [ "a", "b", "c", "d" ],
	];
	Asserts::assertsArray($testData1)->doesNotEqual($testData2);

	// and this should fail
	$testData3 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d",
		"echo"    => [ 1, 2, 3, 4, 5 ],
	];

	$checkpoint->test2Exception = false;
	try {
		Asserts::assertsArray($testData3)->doesNotEqual($testData1);
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