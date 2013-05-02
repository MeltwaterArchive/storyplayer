<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Twitter Stories')
         ->inGroup('Web Browsing')
         ->called('Can open Twitter home page');

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

    	$st->usingBrowser()->gotoPage("https://twitter.com");

    	// get the title of the test page
    	$checkpoint->title = $st->fromBrowser()->getTitle();

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
    	$st->expectsString($checkpoint->title)->equals("Twitter");

});
