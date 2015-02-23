<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object is an instance of a given class');

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
	assertsObject($story)->isInstanceOf('DataSift\Storyplayer\PlayerLib\Story');
	assertsObject($checkpoint)->isInstanceOf('DataSift\Storyplayer\PlayerLib\Story_Checkpoint');

	// and this should fail
	try {
		assertsObject($story)->isInstanceOf('Prose\Prose');
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
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
});