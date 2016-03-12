<?php

use Storyplayer\SPv3\Modules\Asserts;
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

$story->addTestSetup(function() {
	// let's decide on the message we're sending and expecting back
	$checkpoint = Checkpoint::getCheckpoint();
	$checkpoint->expectedMessage = [ "hello, Storyplayer", "you're looking fine today"];
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
		$inPort  = Host::fromHost($hostId)->getStorySetting("zmq.multi.inPort");
		$outPort = Host::fromHost($hostId)->getStorySetting("zmq.multi.outPort");

		$inSocket  = ZeroMQ::usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');
		$outSocket = ZeroMQ::usingZmqContext($context)->connectToHost($hostId, $outPort, 'PULL');

		ZeroMQ::expectsZmqSocket($inSocket)->canSendmultiNonBlocking($checkpoint->expectedMessage);
		$checkpoint->actualMessage = ZeroMQ::fromZmqSocket($outSocket)->recvMulti();
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
	Asserts::assertsArray($checkpoint->actualMessage)->equals($checkpoint->expectedMessage);
});