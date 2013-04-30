<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Twitter UI Stories')
         ->inGroup('Web Browsing')
         ->called('Can log in to Twitter');

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

    // load Twitter login page
    $st->usingBrowser()->gotoPage("https://twitter.com");
    $st->usingBrowser()->waitForTitle(2, "Twitter");  // <- error?

//exit(0);

    //$st->usingForm("js-signin signin")->fillInFields(array("js-username-field email-input" => "DSTW2012", "js-password-field" => "kastaniety12"));
    $st->usingBrowser()->type("DSTW2012")->fieldWithName("session[username_or_email]");
    //$st->usingBrowser()->type("DSTW2012")->elementWithClass("js-username-field email-input");
exit(0);
    $st->usingBrowser()->type("kastaniety12")->fieldWithClass("user-login");
    //-> => "DSTW2012", "js-password-field" => "kastaniety12"));

exit(0);

    $st->usingForm("user-login")->click()->buttonWithText("Sign in");

    // make sure we're definitely logged in
    $st->usingBrowser()->waitForTitle(10, "Twitter");
    $st->expectsBrowser()->has()->imageWithTitle("DSTW2012");
    // get the title of the test page
    //$checkpoint->title = $st->fromBrowser()->getTitle();

    exit(0);

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
