<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('AssertsArray: Can check that data is empty');

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
	$data1 = [];
	assertsArray($data1)->isEmpty();

	// and this should fail
	$checkpoint->data2Exception = false;
	try {
		$data2 = [ "hello, Storyplayer "];
		assertsArray($data2)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->data2Exception = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("data2Exception");
	assertsBoolean($checkpoint->data2Exception)->isTrue();
});