<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is a UUID');

$story->requiresStoryplayerVersion(2);

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
	$checkpoint = getCheckpoint();

	// these should pass
	$uuidData = "de305d54-75b4-431b-adb2-eb6b9e546013";
	assertsString($uuidData)->isUuid();

	// and these should fail
	try {
		$invalidData = "de305d54-75b4-431b-adb2-eb6b9e54601g";
		assertsString($invalidData)->isUuid();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$invalidData = "de305d-5475b4-431b-adb2-eb6b9e546013";
		assertsString($invalidData)->isUuid();
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
	$checkpoint = getCheckpoint();

	for ($i = 1; $i <= 2; $i++) {
		$attribute="test{$i}Passed";
		assertsObject($checkpoint)->hasAttributeWithValue($attribute, true);
	}
});