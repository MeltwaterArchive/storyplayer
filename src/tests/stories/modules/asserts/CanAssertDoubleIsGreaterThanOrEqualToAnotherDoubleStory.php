<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsDouble'])
         ->called('Can check that a double is greater than or equal to another double');

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
	$actualData = 1.12;
	$expectedData1 = 1.11;
	assertsDouble($actualData)->isGreaterThanOrEqualTo($expectedData1);

	$actualData = 1.12;
	$expectedData2 = 1.12;
	assertsDouble($actualData)->isGreaterThanOrEqualTo($expectedData2);

	// and this should fail
	try {
		$expectedData3 = 1.2;
		assertsDouble($actualData)->isGreaterThan($expectedData3);
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