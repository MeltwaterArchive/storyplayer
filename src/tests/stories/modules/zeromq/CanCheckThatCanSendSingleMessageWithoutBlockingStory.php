<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'ZeroMQ'])
         ->called('Can check that can send a single message without blocking');

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

$story->addTestSetup(function() {
	// let's decide on the message we're sending and expecting back
	$checkpoint = getCheckpoint();
	$checkpoint->expectedMessage = "hello, Storyplayer";
	$checkpoint->actualMessage = null;
});

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
		$context = usingZmqContext()->getZmqContext();
		$inPort  = fromHost($hostId)->getAppSetting("zmq.single.inPort");
		$outPort = fromHost($hostId)->getAppSetting("zmq.single.outPort");

		$inSocket  = usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');
		$outSocket = usingZmqContext($context)->connectToHost($hostId, $outPort, 'PULL');

		expectsZmqSocket($inSocket)->canSendNonBlocking($checkpoint->expectedMessage);
		$checkpoint->actualMessage = fromZmqSocket($outSocket)->recv();
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("expectedMessage");
	assertsObject($checkpoint)->hasAttribute("actualMessage");
	assertsString($checkpoint->actualMessage)->equals($checkpoint->expectedMessage);
});