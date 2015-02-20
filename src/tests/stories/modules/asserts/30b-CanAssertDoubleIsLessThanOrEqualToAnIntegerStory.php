<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsDouble'])
         ->called('Can check that a double is less than or equal to an integer');

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
	$actualData = 2.0;
	$expectedData1 = 3;
	assertsDouble($actualData)->isLessThanOrEqualTo($expectedData1);

	$actualData = 2.0;
	$expectedData2 = 2;
	assertsDouble($actualData)->isLessThanOrEqualTo($expectedData2);

	// and these should fail
	try {
		$expectedData3 = 1;
		assertsDouble($actualData)->isLessThanOrEqual2($expectedData3);
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