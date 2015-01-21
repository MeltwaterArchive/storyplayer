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
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/WorkingWithForms.html');

    // get a field from a form
    $checkpoint->field1 = $st->fromForm("test_form")->getValue()->fieldLabelled('Page Name');
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
	$st->assertsObject($checkpoint)->hasAttribute('field1');
	$st->assertsString($checkpoint->field1)->equals("Storyplayer: Working With Forms");
});