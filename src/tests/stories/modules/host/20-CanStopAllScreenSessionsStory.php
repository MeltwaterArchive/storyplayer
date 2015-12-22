<?php

use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	// use the checkpoint to share the name of our screen session
	$checkpoint = Checkpoint::getCheckpoint();
	$checkpoint->sessions = [
		"storyplayer_test_session_1",
		"storyplayer_test_session_2",
		"storyplayer_test_session_3",
		"storyplayer_test_session_4",
		"storyplayer_test_session_5",
	];

	// make sure the session is running on each host
	foreach (Host::getHostsWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			$details = Host::fromHost($hostId)->getScreenSessionDetails($session);
			if (!$details) {
				Host::usingHost($hostId)->startInScreen($session, 'top');
			}
		}
	}
});

$story->addTestTeardown(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	// if we've left the session running, go and kill it off
	foreach (Host::getHostsWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			$details = Host::fromHost($hostId)->getScreenSessionDetails($session);
			if ($details) {
				Host::usingHost($hostId)->stopProcess($details->pid);
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
	$checkpoint = Checkpoint::getCheckpoint();

	foreach (Host::getHostsWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			Host::expectsHost($hostId)->screenIsRunning($session);
		}
	}

	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		Host::usingHost($hostId)->stopAllScreens();
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	foreach (Host::getHostsWithRole('host_target') as $hostId) {
		foreach ($checkpoint->sessions as $session) {
			Host::expectsHost($hostId)->screenIsNotRunning($session);
		}
	}
});
