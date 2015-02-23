<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object is not empty');

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
	$actualData = new stdClass;
	$actualData->attribute1 = null;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = [];
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = true;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = false;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 0.0;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 3.1415927;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 0;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = 99;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = $checkpoint;
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = "";
	assertsObject($actualData)->isNotEmpty();

	$actualData = new stdClass;
	$actualData->attribute1 = "hello, Storyplayer";
	assertsObject($actualData)->isNotEmpty();

	// and this should fail
	$actualData = new stdClass;
	try {
		assertsObject($actualData)->isNotEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("test1Passed");
	assertsBoolean($checkpoint->test1Passed)->equals(true);
});