<?php

use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Supervisor;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // we should have a file for each host in the configuration
    foreach (Host::getHostsWithRole('upload_target') as $hostname) {
        Supervisor::expectsHost($hostname)->programIsRunning('zmq-echo-server');
    }
});