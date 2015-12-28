<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Shell;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("get the IPv4 addresses of our test environment computer(s)");

    // we're going to store them in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->expectedAddresses = [];

    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $result = Shell::onHost($hostId)->runCommand("/sbin/ip addr | grep 'inet ' | tail -n 1 | cut -d '/' -f 1");
        $checkpoint->expectedAddresses[] = trim(str_replace('inet', '', $result->output));
    }

    // all done
    $log->endAction();
});

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("get the IP address of our test environment");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->actualAddresses = [];

    // what are the IP addresses of our test environment, according to
    // the Host module?
    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $checkpoint->actualAddresses[] = Host::fromHost($hostId)->getIpAddress();
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
    $log = Log::usingLog()->startAction("make sure the Shell module returned the correct result");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get what we expected?
    Asserts::assertsArray($checkpoint->actualAddresses)->equals($checkpoint->expectedAddresses);

    // all done
    $log->endAction();
});
