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
    Browser::usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/index.html');

    // get the title of the test page
    $checkpoint->title = Browser::fromBrowser()->getTitle();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = Checkpoint::getCheckpoint();

	// do we have the title we expected?
	Asserts::assertsObject($checkpoint)->hasAttribute('title');
	Asserts::assertsString($checkpoint->title)->equals("Storyplayer: Welcome To The Tests!");
});
