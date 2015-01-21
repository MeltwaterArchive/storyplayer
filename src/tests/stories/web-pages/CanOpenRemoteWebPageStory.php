<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Pages')
         ->called('Can open remote web page');

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
    $st->usingBrowser()->gotoPage("http://news.bbc.co.uk");

    // get the title of the test page
    $checkpoint->title = $st->fromBrowser()->getTitle();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the title we expected?
	$st->assertsObject($checkpoint)->hasAttribute('title');
	$st->assertsString($checkpoint->title)->equals("BBC News - Home");
});