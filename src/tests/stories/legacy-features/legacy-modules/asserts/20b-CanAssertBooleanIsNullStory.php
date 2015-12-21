<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsBoolean'])
         ->called('Can check that a boolean is null');

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
	$checkpoint->test2Passed = false;
	$checkpoint->test3Passed = false;

	// this should pass
	$testData1 = null;
	assertsBoolean($testData1)->isNull();

	// these should all fail
	$testData2 = true;
	try {
		assertsBoolean($testData2)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	$testData3 = false;
	try {
		assertsBoolean($testData3)->isNull();
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

	assertsObject($checkpoint)->hasAttribute('test2Passed');
	assertsBoolean($checkpoint->test2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('test3Passed');
	assertsBoolean($checkpoint->test3Passed)->isTrue();

});