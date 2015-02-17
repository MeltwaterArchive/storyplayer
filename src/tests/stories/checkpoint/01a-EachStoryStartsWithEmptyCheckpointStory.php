<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Stories > Checkpoint')
         ->called('Each story starts with empty checkpoint (pt 1)');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	// do nothing
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = getCheckpoint();
	$checkpoint->thisDataShouldDisappearInPt2 = true;
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute('thisDataShouldDisappearInPt2');
	assertsBoolean($checkpoint->thisDataShouldDisappearInPt2)->isTrue();
});