<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is valid JSON');

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
	$arrayData = json_encode([]);
	assertsString($arrayData)->isValidJson();

	$objectData = json_encode($checkpoint);
	assertsString($objectData)->isValidJson();

	// and these should fail
	try {
		$invalidData = "{ 100";
		assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$invalidData = "[ abcdef01234";
		assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	// PHP 5.6 on Ubuntu accepts 'True' and 'False' as
	// valid JSON, which is a bug :(
	/*
	try {
		$invalidData = "[ True ]";
		assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	try {
		$invalidData = "[ False ]";
		assertsString($invalidData)->isValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
	*/

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