<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Browser;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Log;
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

$story->addPreTestInspection(function() {
	// get the checkpoint
	$checkpoint = Checkpoint::getCheckpoint();

	// what size do we expect?
	$checkpoint->expectedDimensions = array(
		'width' => 400,
		'height' => 400
	);
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("attempt to resize the browser");

	// get the checkpoint, to store data in
	$checkpoint = Checkpoint::getCheckpoint();

    // load our test page
    Browser::usingBrowser()->gotoPage("file://" . __DIR__ . '/../../testpages/index.html');

    // resize the window
    Browser::usingBrowser()->resizeCurrentWindow(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );

    // remember the new size for later
    $checkpoint->actualDimensions = Browser::fromBrowser()->getCurrentWindowSize();

    // did the browser change size?
    Browser::expectsBrowser()->currentWindowSizeIs(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );

    $log->endAction("phase complete");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = Checkpoint::getCheckpoint();

	// do we have the dimensions at all?
	Asserts::assertsObject($checkpoint)->hasAttribute('expectedDimensions');
	Asserts::assertsObject($checkpoint)->hasAttribute('actualDimensions');

	// do they match?
	Asserts::assertsArray($checkpoint->actualDimensions)->equals($checkpoint->expectedDimensions);
});
