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
    $twitter = $st->fromEnvironment()->getAppSettings("twitter");

    // load Twitter login page
    $st->usingBrowser()->gotoPage("https://twitter.com");
    $st->usingBrowser()->waitForTitle(2, "Twitter");  // <- error?

//exit(0);

    $st->usingBrowser()->type($twitter->username)->fieldWithName("session[username_or_email]");
    $st->usingBrowser()->type($twitter->password)->fieldWithName("session[password]");

    // get the 'Sign in' button from the page
    $topElement = $st->fromBrowser()->getTopElement();
    $element    = $topElement->getElement('xpath', '//td/button[normalize-space(text()) = "Sign in"]');
    $element->click();

    //$st->usingBrowser()->click()->buttonWithClass("submit btn primary-btn");
    //$st->usingBrowser()->click()->buttonWithText("Sign in");
    //$st->usingForm("signin-dropdown")->click()->buttonWithText("Sign in");

    // make sure we're definitely logged in
    $st->usingBrowser()->waitForTitle(60, "Twitter");
    //$st->expectsBrowser()->has()->imageWithTitle("DSTW2012");

    // store the title of the test page
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
