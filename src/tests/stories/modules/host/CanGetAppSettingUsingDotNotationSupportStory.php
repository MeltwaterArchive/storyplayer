<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('Host: Can get appSetting using dot.notation.support');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	// this is what we expect to retrieve from the test environment config
	$checkpoint = getCheckpoint();
	$checkpoint->expected = "successfully retrieved this appSetting :)";
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// do nothing
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
	// what value do we expect to retrieve?
	$expected = fromCheckpoint()->get('expected');

	// make sure we have it
    foreach(hostWithRole('host_target') as $hostId) {
    	$actual = fromHost($hostId)->getAppSetting('host.expected');
    	assertsString($actual)->equals($expected);
    }
});