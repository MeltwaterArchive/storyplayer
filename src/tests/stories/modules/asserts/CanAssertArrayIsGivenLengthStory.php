<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('AssertsArray: Can check that array is a given length');

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
	$testData = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d"
	];
	assertsArray($testData)->hasLength(4);

	// and this should fail
	$checkpoint->test2Exception = false;
	try {
		assertsArray($testData)->hasLength(5);
	}
	catch (Exception $e) {
		$checkpoint->test2Exception = true;
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
});