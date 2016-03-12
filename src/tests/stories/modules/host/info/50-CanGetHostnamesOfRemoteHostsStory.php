<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;

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
    $log = Log::usingLog()->startAction("get the hostnames of our test environment computer(s)");

    // we're going to store them in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->expectedHostnames = [];

    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $result = Host::onHost($hostId)->runCommand('hostname');
        $checkpoint->expectedHostnames[] = trim($result->output);
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
    $log = Log::usingLog()->startAction("use Host module to get hostname(s) of our test environment");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->actualHostnames = [];

    // what are the hostnames of our test environment?
    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $checkpoint->actualHostnames[] = Host::fromHost($hostId)->getHostname();
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
    $log = Log::usingLog()->startAction("make sure that the Host module returns the correct hostname(s) for our test environment");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get what we expect?
    Asserts::assertsArray($checkpoint->actualHostnames)->equals($checkpoint->expectedHostnames);

    // all done
    $log->endAction();
});
