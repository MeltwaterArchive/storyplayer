<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\ZeroMQ;
use Storyplayer\SPv2\Stories\BuildStory;

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

$story->addTestSetup(function() {
	// let's decide on the message we're sending and expecting back
	$checkpoint = Checkpoint::getCheckpoint();
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
	$checkpoint = Checkpoint::getCheckpoint();

	foreach(firstHostWithRole("zmq_target") as $hostId) {
		$context = ZeroMQ::usingZmqContext()->getZmqContext();
		$inPort  = Host::fromHost($hostId)->getStorySetting("zmq.single.inPort");
		$outPort = Host::fromHost($hostId)->getStorySetting("zmq.single.outPort");

		$inSocket  = ZeroMQ::usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');
		$outSocket = ZeroMQ::usingZmqContext($context)->connectToHost($hostId, $outPort, 'PULL');

		ZeroMQ::expectsZmqSocket($inSocket)->canSendNonBlocking($checkpoint->expectedMessage);
		$checkpoint->actualMessage = ZeroMQ::fromZmqSocket($outSocket)->recv();
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = Checkpoint::getCheckpoint();

	Asserts::assertsObject($checkpoint)->hasAttribute("expectedMessage");
	Asserts::assertsObject($checkpoint)->hasAttribute("actualMessage");
	Asserts::assertsString($checkpoint->actualMessage)->equals($checkpoint->expectedMessage);
});