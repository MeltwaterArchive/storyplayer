<?php

use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Log;
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
    // what are we doing?
    $log = Log::usingLog()->startAction("set the name of the screen session for this test");

	// use the checkpoint to share the name of our screen session
	$checkpoint = Checkpoint::getCheckpoint();
	$checkpoint->session = "storyplayer_test_session";

    // all done
    $log->endAction();
});

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test session is not running in our test environment");

    $checkpoint = Checkpoint::getCheckpoint();

	// make sure the session isn't running on the host
	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		$details = Host::fromHost($hostId)->getScreenSessionDetails($checkpoint->session);
		if ($details) {
			Host::usingHost($hostId)->stopProcess($details->pid);
		}
	}

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop our test session if it has been left running");

	$checkpoint = Checkpoint::getCheckpoint();

	// if we've left the session running, go and kill it off
	foreach(hostWithRole('host_target') as $hostId) {
		$details = fromHost($hostId)->getScreenSessionDetails($checkpoint->session);
		if ($details) {
			usingHost($hostId)->stopProcess($details->pid);
		}
	}

    // all done
    $log->endAction();
});

$story->addPreTestPrediction(function(){
    throw Exceptions::newStoryShouldFailException();
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("run a short screen session in the test environment");

	$checkpoint = Checkpoint::getCheckpoint();

	foreach(Host::getHostsWithRole('host_target') as $hostId) {
		// this will cause the screen session to terminate straight away
		Host::usingHost($hostId)->startInScreen($checkpoint->session, "ls");
	}

    // all done
    $log->endAction();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("check the test environment to see if the screen session is still running");

	$checkpoint = Checkpoint::getCheckpoint();

	foreach(hostWithRole('host_target') as $hostId) {
		Host::expectsHost($hostId)->screenIsRunning($checkpoint->session);
	}

    // all done
    $log->endAction();
});
