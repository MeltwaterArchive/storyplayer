<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Supervisor"])
         ->called("Can check that a supervised program is running");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // we should have a file for each host in the configuration
    foreach (hostWithRole('upload_target') as $hostId) {
        expectsSupervisor($hostId)->programIsRunning('zmq-echo-server');
    }
});