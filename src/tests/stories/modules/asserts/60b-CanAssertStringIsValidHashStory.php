<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is a valid hash');

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
	$stringData = md5("filename.json");
	assertsString($stringData)->isHash(32);

	// and these should fail
	try {
		$stringData = "hello, Storyplayer";
		assertsString($stringData)->isHash();
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		// valid hex characters, but an odd length
		// hashes are an even number of characters long
		$stringData = "abcdef01234";
		assertsString($stringData)->isHash();
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