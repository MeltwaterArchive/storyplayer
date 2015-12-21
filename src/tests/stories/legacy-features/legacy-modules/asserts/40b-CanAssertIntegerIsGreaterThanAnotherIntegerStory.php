<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsInteger'])
         ->called('Can check that an integer is greater than another integer');

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
	$actualData = 2;
	$expectedData1 = 1;
	assertsInteger($actualData)->isGreaterThan($expectedData1);

	// and these should fail
	try {
		$expectedData2 = $actualData;
		assertsInteger($actualData)->isGreaterThan($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	try {
		$expectedData3 = 2;
		assertsInteger($actualData)->isGreaterThan($expectedData3);
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