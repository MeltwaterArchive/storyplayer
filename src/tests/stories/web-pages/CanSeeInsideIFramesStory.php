<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Pages')
         ->called('Can see inside iFrames');

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

$story->addAction(function(StoryTeller $st) {
	// get the checkpoint, to store data in
	$checkpoint = $st->getCheckpoint();

    // load our test page
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/WorkingWithIFrames.html');

    // get a h1
    $checkpoint->mainHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');

    // switch to the iFrame
    $st->usingBrowser()->switchToIframe('iframe1');

    // get the h1 now
    $checkpoint->iFrameHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the content we expected?
	$st->assertsObject($checkpoint)->hasAttribute('mainHeader');
	$st->assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With IFrames");

	$st->assertsObject($checkpoint)->hasAttribute('iFrameHeader');
	$st->assertsString($checkpoint->iFrameHeader)->equals("IFrame Content");
});