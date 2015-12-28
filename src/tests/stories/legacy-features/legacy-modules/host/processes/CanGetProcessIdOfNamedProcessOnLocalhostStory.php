<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can get process ID of a named process on localhost");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function() {
    // what are we doing?
    $log = usingLog()->startAction("use raw PHP to discover our process ID");

    // get the checkpoint - we're going to store data in here
    $checkpoint = getCheckpoint();

    // store our process ID for final testing
    $checkpoint->expectedPid = getmypid();

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
    $log = usingLog()->startAction("use the Host module to get our process ID");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = getCheckpoint();

    // what does the Host module say?
    $checkpoint->actualPid = fromHost('localhost')->getPid('php');

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
    $log = usingLog()->startAction("make sure the Host module returned the expected PID");

    // the information to guide our checks is in the checkpoint
    $checkpoint = getCheckpoint();

    // what did we get?
    assertsInteger($checkpoint->actualPid)->equals($checkpoint->expectedPid);

    // all done
    $log->endAction();
});
