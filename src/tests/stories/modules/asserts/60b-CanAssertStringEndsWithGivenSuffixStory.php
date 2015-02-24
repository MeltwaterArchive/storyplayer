<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string ends with a given suffix');

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
	$stringData = "hello, Storyplayer";
	assertsString($stringData)->endsWith("player");

	$stringData = "filename.json";
	assertsString($stringData)->endsWith(".json");

	// and these should fail
	try {
		$stringData = "filename.json";
		assertsString($stringData)->endsWith("name");
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$stringData = "filename.json";
		assertsString($stringData)->endsWith(".jso");
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	try {
		$stringData = "filename.json";
		assertsString($stringData)->endsWith("file");
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

	for ($i = 1; $i <= 3; $i++) {
		$attribute="test{$i}Passed";
		assertsObject($checkpoint)->hasAttributeWithValue($attribute, true);
	}
});