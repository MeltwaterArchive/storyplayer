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

	// these should pass
	$arrayData = json_encode([]);
	Asserts::assertsString($arrayData)->isValidJson();

	$objectData = json_encode($checkpoint);
	Asserts::assertsString($objectData)->isValidJson();

	// and these should fail
	try {
		$invalidData = "{ 100";
		Asserts::assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$invalidData = "[ abcdef01234";
		Asserts::assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	// PHP 5.6 on Ubuntu accepts 'True' and 'False' as
	// valid JSON, which is a bug :(
	/*
	try {
		$invalidData = "[ True ]";
		Asserts::assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	try {
		$invalidData = "[ False ]";
		Asserts::assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
	*/

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