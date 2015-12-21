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
	$invalidData = "{ 100";
	Asserts::assertsString($invalidData)->isNotValidJson();

	$invalidData = "[ abcdef01234";
	Asserts::assertsString($invalidData)->isNotValidJson();

	// PHP 5.6 on Ubuntu 15.04 accepts 'True'
	// which is a bug :(
	//$invalidData = "[ True ]";
	//Asserts::assertsString($invalidData)->isNotValidJson();

	//$invalidData = "[ False ]";
	//Asserts::assertsString($invalidData)->isNotValidJson();

	// and these should fail
	try {
		$arrayData = json_encode([]);
		Asserts::assertsString($arrayData)->isNotValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$objectData = json_encode($checkpoint);
		Asserts::assertsString($objectData)->isNotValidJson();
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