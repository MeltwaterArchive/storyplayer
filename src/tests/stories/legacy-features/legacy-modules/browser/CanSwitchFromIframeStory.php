<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
         ->called('Can switch from iFrame to main frame');

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
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/WorkingWithIframes.html');

    // switch to the iFrame
    usingBrowser()->switchToIframe('iframe1');

    // get the h1
    $checkpoint->iFrameHeader = fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');

    // switch back to the main frame
    usingBrowser()->switchToMainFrame();

    // get a h1
    $checkpoint->mainHeader = fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');
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
	assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With IFrames");

	assertsObject($checkpoint)->hasAttribute('iFrameHeader');
	assertsString($checkpoint->iFrameHeader)->equals("IFrame Content");
});
