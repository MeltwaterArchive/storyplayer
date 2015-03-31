<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'ZeroMQ'])
         ->called('Can check where a ZMQ socket is connected');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

$story->addTestCanRunCheck(function() {
	// do we have the ZMQ extension installed?
	expectsZmq()->requirementsAreMet();
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
	$checkpoint = getCheckpoint();

	foreach(firstHostWithRole("zmq_target") as $hostId) {
		// we need to connect
		$context = usingZmqContext()->getZmqContext();
		$inPort  = fromHost($hostId)->getStorySetting("zmq.multi.inPort");
		$inSocket  = usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');

		// now let's make sure we are connected
		expectsZmqSocket($inSocket)->isConnectedToHost($hostId, $inPort);
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