<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

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
	$checkpoint = Checkpoint::getCheckpoint();

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
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 3.1415927);
	Asserts::assertsObject($actualData)->hasAttributeWithValue('attribute5', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', false);
	Asserts::assertsObject($actualData)->hasAttributeWithValue('attribute7', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', "");
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', "hello, Storyplayer");

	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', null);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', []);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', true);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', false);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 0.0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 3.1415927);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 0);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', 99);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', $checkpoint);
	Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', "");

	// and these should fail
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute1', null);
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute2', []);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute3', true);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute4', false);
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute5', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test5Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute6', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test6Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute7', 0);
	}
	catch (Exception $e) {
		$checkpoint->test7Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute8', 99);
	}
	catch (Exception $e) {
		$checkpoint->test8Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute9', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test9Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute10', "");
	}
	catch (Exception $e) {
		$checkpoint->test10Passed = true;
	}
	try {
		Asserts::assertsObject($actualData)->doesNotHaveAttributeWithValue('attribute11', "hello, Storyplayer");
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
	$checkpoint = Checkpoint::getCheckpoint();

	for ($x = 1; $x <= 11; $x++) {
		$attributeName = "test{$x}Passed";
		Asserts::assertsObject($checkpoint)->hasAttribute($attributeName);
		Asserts::assertsBoolean($checkpoint->$attributeName)->equals(true);
	}
});
