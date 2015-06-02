<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get per-user module settings');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// CHECK FOR REQUIRED TEST DATA
//
// ------------------------------------------------------------------------

$story->addTestCanRunCheck(function() {
	// do we have a user dotfile installed?
	if (!file_exists(getenv("HOME") . '/.storyplayer/storyplayer.json')) {
		return false;
	}
});

// ========================================================================
//
// TEST SETUP
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	$checkpoint = getCheckpoint();
	$checkpoint->expectedData = "fred";
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = getCheckpoint();
	$checkpoint->actualData = fromConfig()->getModuleSetting("per-user.data1.value1");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();
	assertsObject($checkpoint)->hasAttribute("expectedData");
	assertsObject($checkpoint)->hasAttribute("actualData");
	assertsString($checkpoint->actualData)->equals($checkpoint->expectedData);
});