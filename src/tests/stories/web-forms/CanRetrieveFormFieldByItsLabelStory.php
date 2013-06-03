<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Browsing')
         ->called('Can retrieve form field by its label');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

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
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/testpage.html');

    // get the title of the test page
    $checkpoint->contents = $st->fromBrowser()->get()->fieldLabelled('');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the title we expected?
	$st->expectsObject($checkpoint)->hasAttribute('title');
	$st->expectsString($checkpoint->title)->equals("Storyplayer: Self-Host Tests Page");
});