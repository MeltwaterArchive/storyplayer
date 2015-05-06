<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is not valid JSON');

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
	$invalidData = "{ 100";
	assertsString($invalidData)->isNotValidJson();

	$invalidData = "[ abcdef01234";
	assertsString($invalidData)->isNotValidJson();

	// PHP 5.6 on Ubuntu 15.04 accepts 'True'
	// which is a bug :(
	//$invalidData = "[ True ]";
	//assertsString($invalidData)->isNotValidJson();

	//$invalidData = "[ False ]";
	//assertsString($invalidData)->isNotValidJson();

	// and these should fail
	try {
		$arrayData = json_encode([]);
		assertsString($arrayData)->isNotValidJson();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$objectData = json_encode($checkpoint);
		assertsString($objectData)->isNotValidJson();
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