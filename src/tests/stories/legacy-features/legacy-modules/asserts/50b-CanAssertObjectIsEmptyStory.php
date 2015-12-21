<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object is empty');

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

	// this should pass
	assertsObject($actualData)->isEmpty();

	// and these should fail
	$actualData = new stdClass;
	$actualData->attribute1 = null;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = [];
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = true;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = false;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 0.0;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test5Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 3.1415927;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test6Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 0;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test7Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 99;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test8Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = $checkpoint;
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test9Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = "";
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test10Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = "hello, Storyplayer";
	try {
		assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e){
		$checkpoint->test11Passed = true;
	}

});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	for ($x = 1; $x <= 11; $x++) {
		$attributeName = "test{$x}Passed";
		assertsObject($checkpoint)->hasAttribute($attributeName);
		assertsBoolean($checkpoint->$attributeName)->equals(true);
	}
});