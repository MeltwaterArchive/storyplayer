<?php

use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\ZeroMQ;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

$story->addTestCanRunCheck(function() {
	// do we have the ZMQ extension installed?
	ZeroMQ::expectsZmq()->requirementsAreMet();
});

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
	// we're going to store the received message in here
	$checkpoint = Checkpoint::getCheckpoint();

	foreach(firstHostWithRole("zmq_target") as $hostId) {
		// we need to connect
		$context = ZeroMQ::usingZmqContext()->getZmqContext();
		$inPort  = Host::fromHost($hostId)->getStorySetting("zmq.multi.inPort");
		$inSocket  = ZeroMQ::usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');

		// now let's make sure we are connected
		ZeroMQ::expectsZmqSocket($inSocket)->isConnectedToHost($hostId, $inPort);
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	// nothing to do
});