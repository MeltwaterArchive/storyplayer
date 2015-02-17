<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsBoolean'])
         ->called('Can check that two booleans are not equal');

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
	$expected1 = false;
	$actual1   = true;
	assertsBoolean($expected1)->doesNotEqual($actual1);

	$expected2 = true;
	$actual2   = false;
	assertsBoolean($expected2)->doesNotEqual($actual2);

	// these should all fail
	$expected3 = true;
	$actual3   = true;
	try {
		assertsBoolean($expected3)->doesNotEqual($actual3);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	$expected4 = false;
	$actual4   = false;
	try {
		assertsBoolean($expected4)->doesNotEqual($actual4);
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
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

	assertsObject($checkpoint)->hasAttribute('test4Passed');
	assertsBoolean($checkpoint->test4Passed)->isTrue();

});