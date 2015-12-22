<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Browser;
use Storyplayer\SPv2\Modules\Checkpoint;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
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

$story->addAction(function() {
	// get the checkpoint, to store data in
	$checkpoint = Checkpoint::getCheckpoint();

    // load our test page
    Browser::usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/WorkingWithIframes.html');

    // get a h1
    $checkpoint->mainHeader = Browser::fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');

    // switch to the iFrame
    Browser::usingBrowser()->switchToIframe('iframe1');

    // get the h1 now
    $checkpoint->iFrameHeader = Browser::fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_iframes');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = Checkpoint::getCheckpoint();

	// do we have the content we expected?
	Asserts::assertsObject($checkpoint)->hasAttribute('mainHeader');
	Asserts::assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With IFrames");

	Asserts::assertsObject($checkpoint)->hasAttribute('iFrameHeader');
	Asserts::assertsString($checkpoint->iFrameHeader)->equals("IFrame Content");
});
