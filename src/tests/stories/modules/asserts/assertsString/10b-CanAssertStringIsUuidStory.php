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
	$uuidData = "de305d54-75b4-431b-adb2-eb6b9e546013";
	Asserts::assertsString($uuidData)->isUuid();

	// and these should fail
	try {
		$invalidData = "de305d54-75b4-431b-adb2-eb6b9e54601g";
		Asserts::assertsString($invalidData)->isUuid();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$invalidData = "de305d-5475b4-431b-adb2-eb6b9e546013";
		Asserts::assertsString($invalidData)->isUuid();
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