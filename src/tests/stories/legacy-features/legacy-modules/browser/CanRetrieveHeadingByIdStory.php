<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
         ->called('Can retrieve a heading by ID');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// get the checkpoint, to store data in
	$checkpoint = getCheckpoint();

    // load our test page
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/index.html');

    // get a h2 by its ID
    $checkpoint->content = fromBrowser()->getText()->fromHeadingWithId('self_test_website');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = getCheckpoint();

	// do we have the content we expected?
	assertsObject($checkpoint)->hasAttribute('content');
	assertsString($checkpoint->content)->equals("Self-Test Website");
});