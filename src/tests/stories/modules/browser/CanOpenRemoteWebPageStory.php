<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Browser;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Stories\BuildStory;

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
    Browser::usingBrowser()->gotoPage("http://news.bbc.co.uk");

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

	// what title are we expecting?
	$expectedTitle = fromStoryplayer()->getStorySetting("modules.http.remotePage.title");

	// do we have the title we expected?
	Asserts::assertsObject($checkpoint)->hasAttribute('title');
	Asserts::assertsString($checkpoint->title)->equals($expectedTitle);
});
