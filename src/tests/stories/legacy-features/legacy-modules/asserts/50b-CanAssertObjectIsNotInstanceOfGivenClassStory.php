<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object is not an instance of a given class');

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

$story->addAction(function() use($story) {
	$checkpoint = getCheckpoint();

	// these should pass
	assertsObject($story)->isNotInstanceOf('Prose\Prose');

	// and these should fail
	try {
		assertsObject($story)->isNotInstanceOf('DataSift\Storyplayer\PlayerLib\Story');
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}

	try {
		assertsObject($checkpoint)->isNotInstanceOf('DataSift\Storyplayer\PlayerLib\Story_Checkpoint');
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

	assertsObject($checkpoint)->hasAttribute("test1Passed");
	assertsBoolean($checkpoint->test1Passed)->equals(true);

	assertsObject($checkpoint)->hasAttribute("test2Passed");
	assertsBoolean($checkpoint->test2Passed)->equals(true);

});