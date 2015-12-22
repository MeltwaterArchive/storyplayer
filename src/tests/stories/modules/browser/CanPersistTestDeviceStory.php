<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Browser;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// keep the test device open
$story->setPersistDevice();

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
    Browser::usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/index.html');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// if this feature is working, the browser should already be open
	// and we can just grab the title
	$title = Browser::fromBrowser()->getTitle();

	// do we have the title we expected?
	Asserts::assertsString($title)->equals("Storyplayer: Welcome To The Tests!");
});
