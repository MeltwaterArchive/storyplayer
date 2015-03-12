<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
         ->called('Can switch between browser windows');

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
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/WorkingWithWindows.html');

    // get the h1
    $checkpoint->mainHeader = fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_windows');

    // open the second window
    usingBrowser()->click()->linkWithText('open a second window');

    // switch to the second window
    usingBrowser()->switchToWindow("Storyplayer: Second Window");

    // get the h1
    $checkpoint->secondHeader = fromBrowser()->getText()->fromHeadingWithId('storyplayer_second_window');

    // close the second window
    // this leaves the browser in a bit of a state
    usingBrowser()->closeCurrentWindow();

    // switch back to the first window
    usingBrowser()->switchToWindow("Storyplayer: Working With Windows");
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
	assertsObject($checkpoint)->hasAttribute('mainHeader');
	assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With Windows");

	assertsObject($checkpoint)->hasAttribute('secondHeader');
	assertsString($checkpoint->secondHeader)->equals("Storyplayer: Second Window");
});