<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('AssertsArray: Can check that two arrays are the same length');

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
	$testData1 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d"
	];
	$testData2 = [ 1, 2, 3, 4 ];
	assertsArray($testData1)->isSameLengthAs($testData2);

	// and these should fail
	$testData3 = [ 1 ];

	$checkpoint->test2Exception = false;
	try {
		assertsArray($testData3)->isSameLengthAs($testData1);
	}
	catch (Exception $e) {
		$checkpoint->test2Exception = true;
	}

	$checkpoint->test3Exception = false;
	try {
		assertsArray($testData1)->isSameLengthAs($testData3);
	}
	catch (Exception $e) {
		$checkpoint->test3Exception = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("test2Exception");
	assertsBoolean($checkpoint->test2Exception)->isTrue();

	assertsObject($checkpoint)->hasAttribute("test3Exception");
	assertsBoolean($checkpoint->test3Exception)->isTrue();
});