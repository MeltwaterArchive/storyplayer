<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsString'])
         ->called('Can check that a string is in an array');

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
	$stringData = "hello, Storyplayer";
	$expected1Data = [ "hello, Storyplayer", "and welcome", "to these tests" ];
	assertsString($stringData)->isIn($expected1Data);

	$expected2Data = [
		"a" => "hello, Storyplayer",
		"b" => "and welcome",
		"c" => "to these tests"
	];
	assertsString($stringData)->isIn($expected2Data);

	// and these should fail
	try {
		$expected3Data = [ "hello, Storyplayer!", "and welcome", "to these tests" ];
		assertsString($stringData)->isIn($expected3Data);
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		$expected4Data = [
			"a" => "hello, Storyplayer!",
			"b" => "and welcome",
			"c" => "to these tests"
		];
		assertsString($stringData)->isIn($expected4Data);
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