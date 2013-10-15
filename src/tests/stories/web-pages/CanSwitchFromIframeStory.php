<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Pages')
         ->called('Can switch from iFrame to main frame');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

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

$story->addAction(function(StoryTeller $st) {
	// get the checkpoint, to store data in
	$checkpoint = $st->getCheckpoint();

    // load our test page
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/WorkingWithIFrames.html');

    // switch to the iFrame
    $st->usingBrowser()->switchToIframe('iframe1');

    // get the h1
    $checkpoint->iFrameHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');

    // switch back to the main frame
    $st->usingBrowser()->switchToMainFrame();

    // get a h1
    $checkpoint->mainHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the content we expected?
	$st->assertsObject($checkpoint)->hasAttribute('mainHeader');
	$st->assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With IFrames");

	$st->assertsObject($checkpoint)->hasAttribute('iFrameHeader');
	$st->assertsString($checkpoint->iFrameHeader)->equals("IFrame Content");
});