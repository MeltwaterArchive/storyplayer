<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Host'])
         ->called('Can stop all screen sessions');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	// use the checkpoint to share the name of our screen session
	$checkpoint = getCheckpoint();
	$checkpoint->sessions = [
		"storyplayer_test_session_1",
		"storyplayer_test_session_2",
		"storyplayer_test_session_3",
		"storyplayer_test_session_4",
		"storyplayer_test_session_5",
	];

	// make sure the session is running on each host
	foreach (hostWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			$details = fromHost($hostId)->getScreenSessionDetails($session);
			if (!$details) {
				usingHost($hostId)->startInScreen($session, 'top');
			}
		}
	}
});

$story->addTestTeardown(function() {
	$checkpoint = getCheckpoint();

	// if we've left the session running, go and kill it off
	foreach (hostWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			$details = fromHost($hostId)->getScreenSessionDetails($session);
			if ($details) {
				usingHost($hostId)->stopProcess($details->pid);
			}
		}
	}
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// make sure all the sessions are running first
	$checkpoint = getCheckpoint();

	foreach (hostWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			expectsHost($hostId)->screenIsRunning($session);
		}
	}

	foreach(hostWithRole('host_target') as $hostId) {
		usingHost($hostId)->stopAllScreens();
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	foreach (hostWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			expectsHost($hostId)->screenIsNotRunning($session);
		}
	}
});