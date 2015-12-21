<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsInteger'])
         ->called('Can check that an integer is less than or equal to a double');

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
	$actualData = 2;
	$expectedData1 = 3.0;
	assertsInteger($actualData)->isLessThanOrEqualTo($expectedData1);

	$actualData = 2;
	$expectedData2 = 2.0;
	assertsInteger($actualData)->isLessThanOrEqualTo($expectedData2);

	// and these should fail
	try {
		$expectedData3 = 1.0;
		assertsInteger($actualData)->isLessThanOrEqualTo($expectedData3);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("test3Passed");
	assertsBoolean($checkpoint->test3Passed)->isTrue();
});