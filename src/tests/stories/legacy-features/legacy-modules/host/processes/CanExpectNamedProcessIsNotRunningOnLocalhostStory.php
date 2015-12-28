<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can expect that a named process is not running on localhost");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("make sure that the Host module sees that process 'php1' is not running on 'localhost'");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = getCheckpoint();

    // what does the Host module think?
    $isRunning = expectsHost('localhost')->processIsNotRunning("php1");

    // all done
    $log->endAction();
});