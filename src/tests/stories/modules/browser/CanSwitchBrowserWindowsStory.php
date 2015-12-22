<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Browser;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

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
    Browser::usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/WorkingWithWindows.html');

    // get the h1
    $checkpoint->mainHeader = Browser::fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_windows');

    // open the second window
    Browser::usingBrowser()->click()->linkWithText('open a second window');

    // switch to the second window
    Browser::usingBrowser()->switchToWindow("Storyplayer: Second Window");

    // get the h1
    $checkpoint->secondHeader = Browser::fromBrowser()->getText()->fromHeadingWithId('storyplayer_second_window');

    // close the second window
    // this leaves the browser in a bit of a state
    Browser::usingBrowser()->closeCurrentWindow();

    // switch back to the first window
    Browser::usingBrowser()->switchToWindow("Storyplayer: Working With Windows");
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
	Asserts::assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With Windows");

	Asserts::assertsObject($checkpoint)->hasAttribute('secondHeader');
	Asserts::assertsString($checkpoint->secondHeader)->equals("Storyplayer: Second Window");
});
