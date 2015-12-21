<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsInteger'])
         ->called('Can check that an integer is not null');

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
	$intData = 1;
	assertsInteger($intData)->isNotNull();

	// and these should fail
	//
	// the NULL test because, well, it is null
	// the rest because they are not valid PHP double values
	try {
		$nullData = null;
		assertsInteger($nullData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->nullTestPassed = true;
	}

	try {
		$arrayData = [];
		assertsInteger($arrayData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->arrayTestPassed = true;
	}

	try {
		$booleanData = true;
		assertsInteger($booleanData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest1Passed = true;
	}

	try {
		$booleanData = false;
		assertsInteger($booleanData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->booleanTest2Passed = true;
	}

	try {
		$doubleData = 0.0;
		assertsInteger($doubleData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest1Passed = true;
	}

	try {
		$doubleData = 3.1415927;
		assertsInteger($doubleData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->doubleTest2Passed = true;
	}

	try {
		$objectData = new stdClass;
		assertsInteger($objectData)->isNotNull();
	}
	catch (Exception $e) {
		$checkpoint->objectTestPassed = true;
	}

	try {
		$stringData = "";
		assertsInteger($stringData)->isNotNull();
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