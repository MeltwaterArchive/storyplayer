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

	// this should pass
	Asserts::assertsObject($actualData)->isEmpty();

	// and these should fail
	$actualData = new stdClass;
	$actualData->attribute1 = null;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = [];
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = true;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = false;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 0.0;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test5Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 3.1415927;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test6Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 0;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test7Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = 99;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test8Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = $checkpoint;
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test9Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = "";
	try {
		Asserts::assertsObject($actualData)->isEmpty();
	}
	catch (Exception $e) {
		$checkpoint->test10Passed = true;
	}

	$actualData = new stdClass;
	$actualData->attribute1 = "hello, Storyplayer";
	try {
		Asserts::assertsObject($actualData)->isEmpty();
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