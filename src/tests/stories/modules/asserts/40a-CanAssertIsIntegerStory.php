<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsInteger'])
         ->called('Can check that data is an integer');

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
	$integerData = 1;
	assertsInteger($integerData)->isInteger();

	// and these should fail
	try {
		$nullData = null;
		assertsInteger($nullData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		assertsInteger($arrayData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		assertsInteger($booleanData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		assertsInteger($booleanData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		assertsInteger($doubleData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		assertsInteger($doubleData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$objectData = new stdClass;
		assertsInteger($objectData)->isInteger();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	try {
		$stringData = "";
		assertsInteger($stringData)->isInteger();
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

	assertsObject($checkpoint)->hasAttribute("nullTestPassed");
	assertsBoolean($checkpoint->nullTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("arrayTestPassed");
	assertsBoolean($checkpoint->arrayTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("booleanTest1Passed");
	assertsBoolean($checkpoint->booleanTest1Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("booleanTest2Passed");
	assertsBoolean($checkpoint->booleanTest2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("doubleTest1Passed");
	assertsBoolean($checkpoint->doubleTest1Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("doubleTest2Passed");
	assertsBoolean($checkpoint->doubleTest2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("stringTestPassed");
	assertsBoolean($checkpoint->stringTestPassed)->isTrue();
});