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
	$stringData = md5("filename.json");
	Asserts::assertsString($stringData)->isHash(32);

	// and these should fail
	try {
		$stringData = "hello, Storyplayer";
		Asserts::assertsString($stringData)->isHash();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		// valid hex characters, but an odd length
		// hashes are an even number of characters long
		$stringData = "abcdef01234";
		Asserts::assertsString($stringData)->isHash();
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