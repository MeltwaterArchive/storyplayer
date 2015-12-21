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
	$stringData = "filename.json";
	Asserts::assertsString($stringData)->doesNotEndWith("name");

	$stringData = "filename.json";
	Asserts::assertsString($stringData)->doesNotEndWith(".jso");

	$stringData = "filename.json";
	Asserts::assertsString($stringData)->doesNotEndWith("file");

	// and these should fail
	try {
		$stringData = "hello, Storyplayer";
		Asserts::assertsString($stringData)->doesNotEndWith("player");
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$stringData = "filename.json";
		Asserts::assertsString($stringData)->doesNotEndWith(".json");
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