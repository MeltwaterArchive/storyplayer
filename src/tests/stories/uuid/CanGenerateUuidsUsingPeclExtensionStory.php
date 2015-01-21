<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

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

$story->addPreTestPrediction(function(StoryTeller $st) {
	// do we have the UUID extension installed?
	$st->expectsUuid()->requirementsAreMet();
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

$story->addAction(function(StoryTeller $st) {
	// get the checkpoint, to store data in
	$checkpoint = $st->getCheckpoint();

	// create a UUID
	$checkpoint->uuid = $st->fromUuid()->generateUuid();
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
	$st->assertsObject($checkpoint)->hasAttribute('uuid');
	$st->assertsString($checkpoint->uuid)->isUuid();
});