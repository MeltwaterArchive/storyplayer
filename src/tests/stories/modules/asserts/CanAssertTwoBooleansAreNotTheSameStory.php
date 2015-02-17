<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsBoolean'])
         ->called('Can check that two booleans are not the same');

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
	$actual1   = $expected1;
	assertsBoolean($expected1)->isNotSameAs($actual1);

	$expected2 = true;
	$actual2   = $expected2;
	assertsBoolean($expected2)->isNotSameAs($actual2);

	// these should all fail
	$expected3 = true;
	$actual3   = &$expected3;
	try {
		assertsBoolean($expected3)->isNotSameAs($actual3);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	$expected4 = false;
	$actual4   = &$expected4;
	try {
		assertsBoolean($expected4)->isNotSameAs($actual4);
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