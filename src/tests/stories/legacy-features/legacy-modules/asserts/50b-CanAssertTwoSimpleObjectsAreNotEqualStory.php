<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that two simple objects not are equal');

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

	// we'll use this in our comparisons
	$actualData = new stdClass;
	$actualData->attribute1 = "hello";
	$actualData->attribute2 = "world";

	// create a different object that uses the same data
	$expectedData1 = new stdClass;
	$expectedData1->attribute1 = "hello";
	$expectedData1->attribute2 = "world";
	$expectedData1->attribute3 = "and welcome!";
	assertsObject($actualData)->doesNotEqual($expectedData1);


	// and this should fail
	try {
		$expectedData2 = new stdClass;
		$expectedData2->attribute2 = "world";
		$expectedData2->attribute1 = "hello";
		assertsObject($actualData)->doesNotEqual($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	try {
		$expectedData3 = new stdClass;
		$expectedData3->attribute1 = "hello";
		$expectedData3->attribute2 = "world";
		assertsObject($actualData)->doesNotEqual($expectedData3);
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