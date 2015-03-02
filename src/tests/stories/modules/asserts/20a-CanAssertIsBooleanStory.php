<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsBoolean'])
         ->called('Can check that data is a boolean');

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
	$goodData1 = true;
	assertsBoolean($goodData1)->isBoolean();

	// these should all fail
	$nullTestData = null;
	try {
		assertsBoolean($nullTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	$arrayTestData = [ 1, 2 ];
	try {
		assertsBoolean($arrayTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	$doubleTestData = 1.1;
	try {
		assertsBoolean($doubleTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->doubleTestPassed = true;
	}

	$intTestData = 1;
	try {
		assertsBoolean($intTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->intTestPassed = true;
	}

	$objectTestData = new stdClass;
	try {
		assertsBoolean($objectTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	$stringTestData = "hello, Storyplayer!";
	try {
		assertsBoolean($stringTestData)->isBoolean();
	}
	catch (Exception $e) {
		$checkpoint->stringTestPassed = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute('nullTestPassed');
	assertsBoolean($checkpoint->nullTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('arrayTestPassed');
	assertsBoolean($checkpoint->arrayTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('intTestPassed');
	assertsBoolean($checkpoint->intTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('doubleTestPassed');
	assertsBoolean($checkpoint->doubleTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('objectTestPassed');
	assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute('stringTestPassed');
	assertsBoolean($checkpoint->stringTestPassed)->isTrue();

});