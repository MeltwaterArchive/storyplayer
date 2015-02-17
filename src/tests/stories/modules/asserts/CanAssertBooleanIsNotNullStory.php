<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsBoolean'])
         ->called('Can check that a boolean is not null');

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
	$testData1 = true;
	assertsBoolean($testData1)->isNotNull();

	$testData2 = false;
	assertsBoolean($testData2)->isNotNull();

	// these should all fail
	$testData3 = null;
	try {
		assertsBoolean($testData3)->isNotNull();
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

	assertsObject($checkpoint)->hasAttribute('test3Passed');
	assertsBoolean($checkpoint->test3Passed)->isTrue();

});