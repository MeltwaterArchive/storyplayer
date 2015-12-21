<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsDouble'])
         ->called('Can check that a double is less than another double');

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

	// this should pass
	$actualData = 1.12;
	$expectedData1 = 1.13;
	assertsDouble($actualData)->isLessThan($expectedData1);

	// and these should fail
	try {
		$expectedData2 = $actualData;
		assertsDouble($actualData)->isLessThan($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	try {
		$expectedData3 = 1.11;
		assertsDouble($actualData)->isLessThan($expectedData3);
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

	assertsObject($checkpoint)->hasAttribute("test2Passed");
	assertsBoolean($checkpoint->test2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("test3Passed");
	assertsBoolean($checkpoint->test3Passed)->isTrue();
});