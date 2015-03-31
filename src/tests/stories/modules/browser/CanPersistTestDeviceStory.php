<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
         ->called('Can persist the test device');

// keep the test device open
$story->setPersistDevice();

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
    // load our test page
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/index.html');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// if this feature is working, the browser should already be open
	// and we can just grab the title
	$title = fromBrowser()->getTitle();

	// do we have the title we expected?
	assertsString($title)->equals("Storyplayer: Welcome To The Tests!");

	// all done - shut down the browser
	stopDevice();
});