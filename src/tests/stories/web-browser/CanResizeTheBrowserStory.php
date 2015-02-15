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

$story->addPreTestInspection(function() {
	// get the checkpoint
	$checkpoint = getCheckpoint();

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
	// get the checkpoint, to store data in
	$checkpoint = getCheckpoint();

    // load our test page
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/index.html');

    // resize the window
    usingBrowser()->resizeCurrentWindow(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );

    // remember the new size for later
    $checkpoint->actualDimensions = fromBrowser()->getCurrentWindowSize();

    // did the browser change size?
    expectsBrowser()->currentWindowSizeIs(
    	$checkpoint->expectedDimensions['width'],
    	$checkpoint->expectedDimensions['height']
    );
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = getCheckpoint();

	// do we have the dimensions at all?
	assertsObject($checkpoint)->hasAttribute('expectedDimensions');
	assertsObject($checkpoint)->hasAttribute('actualDimensions');

	// do they match?
	assertsArray($checkpoint->actualDimensions)->equals($checkpoint->expectedDimensions);
});