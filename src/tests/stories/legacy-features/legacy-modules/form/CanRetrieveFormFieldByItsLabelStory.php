<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Form'])
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

$story->addAction(function() {
	// get the checkpoint, to store data in
	$checkpoint = getCheckpoint();

    // load our test page
    usingBrowser()->gotoPage("file://" . __DIR__ . '/../../../testpages/WorkingWithForms.html');

    // get a field from a form
    $checkpoint->field1 = fromForm("test_form")->getValue()->fieldLabelled('Page Name');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = getCheckpoint();

	// do we have the title we expected?
	assertsObject($checkpoint)->hasAttribute('field1');
	assertsString($checkpoint->field1)->equals("Storyplayer: Working With Forms");
});
