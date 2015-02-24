<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is not null');

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
	$stringData = "";
	assertsString($stringData)->isNotNull();

	$stringData = "hello, Storyplayer";
	assertsString($stringData)->isNotNull();

	// and these should fail
	try {
		$nullData = null;
		assertsString($nullData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		assertsString($arrayData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		assertsString($booleanData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		assertsString($booleanData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		assertsString($doubleData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		assertsString($doubleData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$intData = 0;
		assertsString($intData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->intTest1Passed = true;
	}

	try {
		$intData = 1;
		assertsString($intData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->intTest2Passed = true;
	}

	try {
		$objectData = $checkpoint;
		assertsString($objectData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
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

	assertsObject($checkpoint)->hasAttribute("intTest1Passed");
	assertsBoolean($checkpoint->intTest1Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("intTest2Passed");
	assertsBoolean($checkpoint->intTest2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	assertsBoolean($checkpoint->objectTestPassed)->isTrue();
});