<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object does not have an attribute with a given value');

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
	$actualData->attribute1 = null;
	$actualData->attribute2 = [];
	$actualData->attribute3 = true;
	$actualData->attribute4 = false;
	$actualData->attribute5 = 0.0;
	$actualData->attribute6 = 3.1415927;
	$actualData->attribute7 = 0;
	$actualData->attribute8 = 99;
	$actualData->attribute9 = $checkpoint;
	$actualData->attribute10 = "";
	$actualData->attribute11 = "hello, Storyplayer";

	// these should pass
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 3.1415927);
	assertsObject($actualData)->hasAttributeWithValue('attribute5', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', false);
	assertsObject($actualData)->hasAttributeWithValue('attribute7', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', "");
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', "hello, Storyplayer");

	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', null);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', []);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', true);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', false);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 0.0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 3.1415927);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 0);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 99);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', $checkpoint);
	assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', "");

	// and these should fail
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', null);
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', []);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', true);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', false);
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test5Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test6Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 0);
	}
	catch (Exception $e) {
		$checkpoint->test7Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 99);
	}
	catch (Exception $e) {
		$checkpoint->test8Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test9Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', "");
	}
	catch (Exception $e) {
		$checkpoint->test10Passed = true;
	}
	try {
		assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', "hello, Storyplayer");
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
