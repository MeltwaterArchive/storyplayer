<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Pages')
         ->called('Can switch between browser windows');

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
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/WorkingWithWindows.html');

    // get the h1
    $checkpoint->mainHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_working_with_windows');

    // open the second window
    $st->usingBrowser()->click()->linkWithText('open a second window');

    // switch to the second window
    $st->usingBrowser()->switchToWindow("Storyplayer: Second Window");

    // get the h1
    $checkpoint->secondHeader = $st->fromBrowser()->getText()->fromHeadingWithId('storyplayer_second_window');

    // close the second window
    // this leaves the browser in a bit of a state
    $st->usingBrowser()->closeCurrentWindow();

    // switch back to the first window
    $st->usingBrowser()->switchToWindow("Storyplayer: Working With Windows");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	$checkpoint = $st->getCheckpoint();

	// do we have the content we expected?
	$st->assertsObject($checkpoint)->hasAttribute('mainHeader');
	$st->assertsString($checkpoint->mainHeader)->equals("Storyplayer: Working With Windows");

	$st->assertsObject($checkpoint)->hasAttribute('secondHeader');
	$st->assertsString($checkpoint->secondHeader)->equals("Storyplayer: Second Window");
});