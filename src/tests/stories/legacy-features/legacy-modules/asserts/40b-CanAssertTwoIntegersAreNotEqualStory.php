<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsInteger'])
         ->called('Can check that two integers are not equal');

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
	assertsInteger($actualData)->doesNotEqual($expectedData1);

	// and these should fail
	try {
		$expectedData2 = 2;
		assertsInteger($actualData)->doesNotEqual($expectedData2);
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

	assertsObject($checkpoint)->hasAttribute("test2Passed");
	assertsBoolean($checkpoint->test2Passed)->isTrue();
});