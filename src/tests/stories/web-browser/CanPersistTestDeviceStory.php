<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Browsing')
         ->called('Can persist the test device');

// keep the test device open
$story->setPersistDevice();

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
    // load our test page
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/index.html');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
	// if this feature is working, the browser should already be open
	// and we can just grab the title
	$title = $st->fromBrowser()->getTitle();

	// do we have the title we expected?
	$st->assertsString($title)->equals("Storyplayer: Welcome To The Tests!");
});