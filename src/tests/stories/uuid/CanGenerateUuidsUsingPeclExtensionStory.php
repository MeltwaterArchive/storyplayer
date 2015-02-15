<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('UUID')
         ->called('Can generate UUIDs using PECL extension');

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

$story->addPreTestPrediction(function() {
	// do we have the UUID extension installed?
	expectsUuid()->requirementsAreMet();
});

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

	// create a UUID
	$checkpoint->uuid = fromUuid()->generateUuid();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// get the checkpoint
	$checkpoint = getCheckpoint();

	// do we have the content we expected?
	assertsObject($checkpoint)->hasAttribute('uuid');
	assertsString($checkpoint->uuid)->isUuid();
});