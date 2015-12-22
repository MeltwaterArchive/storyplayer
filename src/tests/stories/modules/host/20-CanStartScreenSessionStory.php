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
	$checkpoint->session = "storyplayer_test_session";

	// make sure the session isn't running on the host
	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		$details = Host::fromHost($hostId)->getScreenSessionDetails($checkpoint->session);
		if ($details) {
			Host::usingHost($hostId)->stopProcess($details->pid);
		}
	}
});

$story->addTestTeardown(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	// if we've left the session running, go and kill it off
	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		$details = Host::fromHost($hostId)->getScreenSessionDetails($checkpoint->session);
		if ($details) {
			Host::usingHost($hostId)->stopProcess($details->pid);
		}
	}
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		Host::usingHost($hostId)->startInScreen($checkpoint->session, "top");
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	foreach(hostWithRole('host_target') as $hostId) {
		Host::expectsHost($hostId)->screenIsRunning($checkpoint->session);
	}
});
