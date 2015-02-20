<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsDouble'])
         ->called('Can check that a double is null');

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
	$nullData = null;
	assertsDouble($nullData)->isNull();

	// and these should fail
	try {
		$doubleData = 1.1;
		assertsDouble($doubleData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->doubleTestPassed = true;
	}

	try {
		$arrayData = [];
		assertsDouble($arrayData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		assertsDouble($booleanData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		assertsDouble($booleanData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$intData = 0;
		assertsDouble($intData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->intTest1Passed = true;
	}

	try {
		$intData = 11;
		assertsDouble($intData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->intTest2Passed = true;
	}

	try {
		$objectData = new stdClass;
		assertsDouble($objectData)->isNull();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	try {
		$stringData = "";
		assertsDouble($stringData)->isNull();
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

	assertsObject($checkpoint)->hasAttribute("doubleTestPassed");
	assertsBoolean($checkpoint->doubleTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("arrayTestPassed");
	assertsBoolean($checkpoint->arrayTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("booleanTest1Passed");
	assertsBoolean($checkpoint->booleanTest1Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("booleanTest2Passed");
	assertsBoolean($checkpoint->booleanTest2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("intTest1Passed");
	assertsBoolean($checkpoint->intTest1Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("intTest2Passed");
	assertsBoolean($checkpoint->intTest2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("objectTestPassed");
	assertsBoolean($checkpoint->objectTestPassed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("stringTestPassed");
	assertsBoolean($checkpoint->stringTestPassed)->isTrue();
});