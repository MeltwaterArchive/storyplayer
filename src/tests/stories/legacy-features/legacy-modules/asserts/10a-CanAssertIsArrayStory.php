<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('AssertsArray: Can check that data is an array');

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
	assertsArray($data1)->isArray();

	// and this should fail
	$checkpoint->data2Exception = false;
	try {
		$data2 = "";
		assertsArray($data2)->isArray();
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