<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Browsing')
         ->called('Can resize the web browser');

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

$story->addPreTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

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

$story->addAction(function(StoryTeller $st) {
	// get the checkpoint, to store data in
	$checkpoint = $st->getCheckpoint();

    // load our test page
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/index.html');

    // resize the window
    $st->usingBrowser()->resizeCurrentWindow(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );

    // remember the new size for later
    $checkpoint->actualDimensions = $st->fromBrowser()->getCurrentWindowSize();

    // did the browser change size?
    $st->expectsBrowser()->currentWindowSizeIs(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the dimensions at all?
	$st->assertsObject($checkpoint)->hasAttribute('expectedDimensions');
	$st->assertsObject($checkpoint)->hasAttribute('actualDimensions');

	// do they match?
	$st->assertsArray($checkpoint->actualDimensions)->equals($checkpoint->expectedDimensions);
});