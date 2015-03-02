<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string does not end with a given suffix');

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
	$stringData = "filename.json";
	assertsString($stringData)->doesNotEndWith("name");

	$stringData = "filename.json";
	assertsString($stringData)->doesNotEndWith(".jso");

	$stringData = "filename.json";
	assertsString($stringData)->doesNotEndWith("file");

	// and these should fail
	try {
		$stringData = "hello, Storyplayer";
		assertsString($stringData)->doesNotEndWith("player");
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$stringData = "filename.json";
		assertsString($stringData)->doesNotEndWith(".json");
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}


});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	for ($i = 1; $i <= 2; $i++) {
		$attribute="test{$i}Passed";
		assertsObject($checkpoint)->hasAttributeWithValue($attribute, true);
	}
});