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

	// these should pass
	$stringData = "hello, Storyplayer";
	$expected1Data = [ "hello, Storyplayer", "and welcome", "to these tests" ];
	Asserts::assertsString($stringData)->isIn($expected1Data);

	$expected2Data = [
		"a" => "hello, Storyplayer",
		"b" => "and welcome",
		"c" => "to these tests"
	];
	Asserts::assertsString($stringData)->isIn($expected2Data);

	// and these should fail
	try {
		$expected3Data = [ "hello, Storyplayer!", "and welcome", "to these tests" ];
		Asserts::assertsString($stringData)->isIn($expected3Data);
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$expected4Data = [
			"a" => "hello, Storyplayer!",
			"b" => "and welcome",
			"c" => "to these tests"
		];
		Asserts::assertsString($stringData)->isIn($expected4Data);
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

	for ($i = 1; $i <= 2; $i++) {
		$attribute="test{$i}Passed";
		Asserts::assertsObject($checkpoint)->hasAttributeWithValue($attribute, true);
	}
});