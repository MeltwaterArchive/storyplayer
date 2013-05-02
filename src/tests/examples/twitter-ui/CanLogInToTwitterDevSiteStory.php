<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Twitter UI Stories')
         ->inGroup('Web Browsing')
         ->called('Can log in to Twitter Developer site');

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

    // load Twitter Developer site login page
    $st->usingBrowser()->gotoPage("https://dev.twitter.com/user/login");
    //$fid = $st->fromBrowser()->getElementById("user-login");
    $st->usingForm("user-login")->fillInFields(array("edit-name" => "DSTW2012", "edit-pass" => "kastaniety12"));
    $st->usingForm("user-login")->click()->buttonWithText("Log in");

    // get the title of the test page
    $checkpoint->title = $st->fromBrowser()->getTitle();

    exit(0);

    // make sure we're definitely logged in
    $st->expectsBrowser()->has()->imageWithTitle("DSTW2012");
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
	$st->expectsString($checkpoint->title)->equals("Twitter Developers");
});
